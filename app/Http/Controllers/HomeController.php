<?php

namespace App\Http\Controllers;

use App\Models\MembershipSubscriptionPlan\MembershipSubscriptionPlan;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\PhotoGallery\PhotoGallery;
use App\Models\VideoGallery\VideoGallery;
use App\Models\Languages\Languages;
use App\Models\Skills\Skills;
use App\Models\Genres\Genres;
use App\Models\RoleTypes\RoleTypes;
use App\Models\Accents\Accents;
use App\Models\Like\Like;
use App\Models\Sliders\Sliders;
use App\Models\Settings\Settings;
use App\Models\UsersPersonalityTraits\UsersPersonalityTraits;
use App\Models\Country\Country;
use App\Models\UserFeed\UserFeed;
use App\Notifications\PersonalityTraits;
use App\Models\Invites\Invites;
use App\User;
use Auth;
use Illuminate\View\View;
use Stripe;
use Image;
use Validator;
use App\Http\Controllers\UserNetworkController;

use Spatie\Activitylog\Models\Activity;

class HomeController extends Controller
{
    protected $upload_path;
    protected $cv_path;
    protected $gallery_upload_path;
    protected $video_upload_path;
    protected $storage;

    public function __construct()
    {
        $this -> upload_path = 'img' . DIRECTORY_SEPARATOR . 'profile_picture' . DIRECTORY_SEPARATOR;
        $this -> cv_path = 'cv' . DIRECTORY_SEPARATOR;
        $this -> gallery_upload_path = 'img' . DIRECTORY_SEPARATOR . 'user_gallery' . DIRECTORY_SEPARATOR;
        $this -> video_upload_path = 'img' . DIRECTORY_SEPARATOR . 'video_gallery' . DIRECTORY_SEPARATOR;
        $this -> storage = Storage ::disk('public');
    }


    /**
     * Show the home Page.
     *
     * @return Renderable
     */
    public function index()
    {

        $slider = Sliders ::get() -> toArray();
        $settings = Settings ::all();
        return view('home', compact('slider', 'settings'));
    }

    /**
     * Show the User profile.
     *
     * @return Renderable
     */
    public function userProfile()
    {
        $languages = Languages ::all();
        $skills = Skills ::all();
        $genres = Genres ::all();
        $accents = Accents ::all();
        $roletypes = RoleTypes ::all();
        $eye_colours = User ::groupBy('eye_colour') -> whereNotNull('eye_colour') -> pluck('eye_colour');
        $hair_colours = User ::groupBy('hair_colour') -> whereNotNull('hair_colour') -> pluck('hair_colour');
        return view('user.profile', compact('languages', 'skills', 'genres', 'accents', 'roletypes', 'hair_colours', 'eye_colours'));
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     * @throws Stripe\Exception\ApiErrorException
     */
    public function userDashboard()
    {
        $subscriptions = DB ::table('subscriptions') -> where('user_id', Auth ::user() -> id) -> get();
        if (count($subscriptions) > 1) {
            foreach ($subscriptions as $subscription) {
                if ($subscription -> trial_ends_at != '') {
                    DB ::table('subscriptions') -> where('id', $subscription -> id) -> delete();
                }
            }
        }
        if (Auth ::user() -> owner -> trial_ends_at != '' && Auth ::user() -> owner -> stripe_id == Auth ::user() -> username && Auth ::user() -> planid != '') {
            Auth ::user() -> update(['status' => 0]);
            DB ::table('subscriptions') -> where('user_id', Auth ::user() -> id) -> update(['stripe_status' => 'trialing']);
        }
        if (Auth ::user() -> owner -> stripe_id == Auth ::user() -> username) {
            $count = Like ::where('profile_id', Auth ::user() -> id) -> count();
            $customPlan = Auth ::user() -> owner;
            $invites = Invites ::where('user_id', Auth ::id()) -> paginate(10);
            return view('user.dashboard', compact('customPlan', 'count', 'invites'));
        } else {
            Stripe\Stripe ::setApiKey(config('services.stripe.secret'));
            if (Auth ::user() -> subscription('main')) {
                $count = likeCount(Auth ::user() -> id);

                $plan = Stripe\Plan ::retrieve(Auth ::user() -> subscription('main') -> stripe_plan);
                $subscription = \Stripe\Subscription ::retrieve(Auth ::user() -> subscription('main') -> stripe_id);
                $cards = [];
                $defaultPaymentMethod = '';
                if (Auth ::user() -> hasPaymentMethod()) {
                    foreach (Auth ::user() -> paymentMethods() as $key => $value) {
                        $cards[] = $value -> asStripePaymentMethod();
                    }
                    $defaultPaymentMethod = Auth ::user() -> defaultPaymentMethod() -> asStripePaymentMethod() -> id;
                }
                $invites = Invites ::where('user_id', Auth ::id()) -> paginate(10);

                return view('user.dashboard', compact('plan', 'count', 'subscription', 'cards', 'defaultPaymentMethod', 'invites'));
            } else {
                return view('user.dashboard');
            }
        }
    }

    /**
     * Add User Stripe Card.
     * @param Request $request
     * @return false|string
     */
    public function addCard(Request $request)
    {
        $input = $request -> all();

        Stripe\Stripe ::setApiKey(config('services.stripe.secret'));
        try {
            $paymentMethod = \Stripe\PaymentMethod ::create([
                'type' => 'card',
                'card' => [
                    'number' => $input['number'],
                    'exp_month' => $input['exp_month'],
                    'exp_year' => $input['exp_year'],
                    'cvc' => $input['cvc']
                ]
            ]);
            Auth ::user() -> addPaymentMethod($paymentMethod);
            if (!Auth ::user() -> hasPaymentMethod()) {
                Auth ::user() -> updateDefaultPaymentMethod($paymentMethod);
            }
            return json_encode(array('status' => 1, 'msg' => 'Card Added Successfully'));
        } catch (\Stripe\Exception\RateLimitException $e) {
            return json_encode(array('status' => 0, 'msg' => $e -> getMessage()));
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            return json_encode(array('status' => 0, 'msg' => $e -> getMessage()));
        } catch (\Stripe\Exception\AuthenticationException $e) {
            return json_encode(array('status' => 0, 'msg' => $e -> getMessage()));
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            return json_encode(array('status' => 0, 'msg' => $e -> getMessage()));
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return json_encode(array('status' => 0, 'msg' => $e -> getMessage()));
        } catch (Exception $e) {
            return json_encode(array('status' => 0, 'msg' => $e -> getMessage()));
        }

    }

    /**
     * get User Stripe Card.
     * @param Request $request
     * @return false|string
     */
    public function getCard(Request $request)
    {
        $input = $request -> all();

        Stripe\Stripe ::setApiKey(config('services.stripe.secret'));
        try {
            $paymentMethod = \Stripe\PaymentMethod ::retrieve(
                $input['cardid']
            );
            return json_encode(array('status' => 1, 'card' => $paymentMethod));
        } catch (\Stripe\Exception\RateLimitException $e) {
            return json_encode(array('status' => 0, 'msg' => $e -> getMessage()));
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            return json_encode(array('status' => 0, 'msg' => $e -> getMessage()));
        } catch (\Stripe\Exception\AuthenticationException $e) {
            return json_encode(array('status' => 0, 'msg' => $e -> getMessage()));
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            return json_encode(array('status' => 0, 'msg' => $e -> getMessage()));
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return json_encode(array('status' => 0, 'msg' => $e -> getMessage()));
        } catch (Exception $e) {
            return json_encode(array('status' => 0, 'msg' => $e -> getMessage()));
        }

    }

    /**
     * Update User Stripe Card.
     * @param Request $request
     * @return false|string
     */
    public function updateCard(Request $request)
    {
        $input = $request -> all();

        Stripe\Stripe ::setApiKey(config('services.stripe.secret'));
        try {
            $paymentMethodUpdate = Stripe\PaymentMethod ::update(
                $input['cardid'],
                [
                    'card' =>
                        [
                            'exp_month' => $input['exp_month'],
                            'exp_year' => $input['exp_year'],
                        ]
                ]
            );
            return json_encode(array('status' => 1, 'msg' => 'Card Updated Successfully'));
        } catch (\Stripe\Exception\RateLimitException $e) {
            return json_encode(array('status' => 0, 'msg' => $e -> getMessage()));
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            return json_encode(array('status' => 0, 'msg' => $e -> getMessage()));
        } catch (\Stripe\Exception\AuthenticationException $e) {
            return json_encode(array('status' => 0, 'msg' => $e -> getMessage()));
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            return json_encode(array('status' => 0, 'msg' => $e -> getMessage()));
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return json_encode(array('status' => 0, 'msg' => $e -> getMessage()));
        } catch (Exception $e) {
            return json_encode(array('status' => 0, 'msg' => $e -> getMessage()));
        }

    }

    /**
     * Delete User Stripe Card.
     * @param Request $request
     * @return RedirectResponse
     */
    public function deleteCard(Request $request)
    {
        $input = $request -> all();
        Stripe\Stripe ::setApiKey(config('services.stripe.secret'));
        try {
            $paymentMethod = \Stripe\PaymentMethod ::retrieve(
                $input['cardid']
            );
            Auth ::user() -> removePaymentMethod($paymentMethod);
            return redirect() -> back() -> with('delete-card', 'Card Deleted Successfully.');
        } catch (\Stripe\Exception\RateLimitException $e) {
            return redirect() -> back() -> with('error', $e -> getMessage());
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            return redirect() -> back() -> with('error', $e -> getMessage());
        } catch (\Stripe\Exception\AuthenticationException $e) {
            return redirect() -> back() -> with('error', $e -> getMessage());
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            return redirect() -> back() -> with('error', $e -> getMessage());
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return redirect() -> back() -> with('error', $e -> getMessage());
        } catch (Exception $e) {
            return redirect() -> back() -> with('error', $e -> getMessage());
        }

    }

    /**
     * Set Default Stripe Card.
     * @param Request $request
     * @return false|string
     */
    public function setDefaultCard(Request $request)
    {
        $input = $request -> all();
        Stripe\Stripe ::setApiKey(config('services.stripe.secret'));
        try {
            $paymentMethod = \Stripe\PaymentMethod ::retrieve(
                $input['cardid']
            );
            Auth ::user() -> updateDefaultPaymentMethod($paymentMethod);
            return json_encode(array('status' => 1, 'msg' => 'Card Set as Default Successfully'));
        } catch (\Stripe\Exception\RateLimitException $e) {
            return json_encode(array('status' => 0, 'msg' => $e -> getMessage()));
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            return json_encode(array('status' => 0, 'msg' => $e -> getMessage()));
        } catch (\Stripe\Exception\AuthenticationException $e) {
            return json_encode(array('status' => 0, 'msg' => $e -> getMessage()));
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            return json_encode(array('status' => 0, 'msg' => $e -> getMessage()));
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return json_encode(array('status' => 0, 'msg' => $e -> getMessage()));
        } catch (Exception $e) {
            return json_encode(array('status' => 0, 'msg' => $e -> getMessage()));
        }

    }


    /**
     * Save User Profile Picture
     *
     * @param Request $request
     * @return void
     */
    public function sessionvalue(Request $request)
    {
        $input = $request -> all();
        $request -> session() -> put('usertypeTransferType', $input['usertype_transfer']);
        $data = $request -> session() -> all();
        $sessionvalue = strtolower($data['usertypeTransferType']);
        echo json_encode($sessionvalue);
    }

    /**
     * @param Request $request
     * @param \App\Http\Controllers\UserNetworkController $network
     */
    public function saveProfilePictute(Request $request, UserNetworkController $network)
    {
        $input = $request -> all();


        list($type, $input['image']) = explode(';', $input['image']);
        list(, $input['image']) = explode(',', $input['image']);


        $data = base64_decode($input['image']);
        $image_name = time() . '.png';
        $path = public_path() . "/img/profile_picture/" . $image_name;
        file_put_contents($path, $data);

        $img = Image ::make($input['image']) -> resize(225, 225);
        if (!file_exists(public_path() . "/img/profile_picture/225")) {
            mkdir(public_path() . "/img/profile_picture/225", 0777, true);
        }
        $img -> save(public_path() . "/img/profile_picture" . '/225/' . $image_name . '', 100);

        if (Auth ::user() -> profile_picture != '' && file_exists(public_path('img/profile_picture/') . Auth ::user() -> profile_picture)) {
            unlink(public_path('img/profile_picture/') . Auth ::user() -> profile_picture);
        }
        if (Auth ::user() -> profile_picture != '' && file_exists(public_path('img/profile_picture/225') . Auth ::user() -> profile_picture)) {
            unlink(public_path('img/profile_picture/225') . Auth ::user() -> profile_picture);
        }

        Auth ::user() -> update(['profile_picture' => $image_name]);

        activity() -> causedBy(Auth ::user()) -> log('Changed Profile Picture');

        $input['properties'] = json_encode(['profile_pic' => $image_name]);
        $input['user_id'] = Auth ::id();
        $feed = UserFeed ::create($input);

        $message = ' Uploaded their Profile Picture';
        if (isset(Auth ::user() -> friendsOfMineNetwork) && count(Auth ::user() -> friendsOfMineNetwork)) {
            $network -> sendNetworkNotification(Auth ::user() -> friendsOfMineNetwork, $message);
        }
        if (isset(Auth ::user() -> friendOfNetwork) && count(Auth ::user() -> friendOfNetwork)) {
            $network -> sendNetworkNotification(Auth ::user() -> friendOfNetwork, $message);
        }

        echo $image_name;
    }

    /**
     * @param Request $request
     */
    public function uploadCv(Request $request)
    {
        $input = $request -> all();

        $cv = $input['cv'];
        $fileName = time() . $cv -> getClientOriginalName();
        $this -> storage -> put($this -> cv_path . $fileName, file_get_contents($cv -> getRealPath()));
        Auth ::user() -> update(['cv' => $fileName]);
        echo $fileName;
    }

    /**
     * Update User profile
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        $validator = Validator ::make($request -> all(), [
            'gender' => 'required',
            'first_name' => 'regex:/^[\pL\s\-]+$/u',
            'last_name' => 'regex:/^[\pL\s\-]+$/u',
            'city' => 'nullable|regex:/^[\pL\s\-]+$/u',
            'country' => 'nullable|regex:/^[\pL\s\-]+$/u',
            'eye_colour' => 'required',
            'height' => 'required',
            'genre' => 'required',
            'languages' => 'required',
            'skills' => 'required',
            'playing_age' => 'nullable|regex:/(^[0-9 -]+$)+/',
            'caption' => 'nullable|max:120',
            'profile_picture' => 'required',
            // 'top_shows.*.link'       => 'required_with:top_shows.*.name',
            // 'favourite_films.*.link' => 'required_with:favourite_films.*.name',
            // 'top_songs.*.link'       => 'required_with:top_songs.*.name',
        ], [
            'first_name.regex' => 'The First Name may only contain letters',
            'last_name.regex' => 'The Last Name may only contain letters',
            'city.regex' => 'The City may only contain letters',
            'country.regex' => 'The Country may only contain letters',
            'eye_colour.required' => 'The Eye Colour is required',
            'height.required' => 'The Height is required',
            'genre.required' => 'The Genre is required',
            'languages.required' => 'The Languages is required',
            'skills.required' => 'The Skills is required',
            'playing_age.numeric' => 'The Playing Age may only contain Number',
            'profile_picture.required' => 'Please Select profile picture',
            // 'top_shows.*.link.required_with' => 'The Playing Age may only contain Number',
            // 'favourite_films.*.link.required_with' => 'The Favourite Film Link Must Be Required',
            // 'top_songs.*.link.required_with' => 'The Song Link Must Be Required',
        ]);

        $validator -> validate();

        $input = $request -> except('_token');

        if (Auth ::user() -> owner -> stripe_id != Auth ::user() -> username && $input['status'] == '1') {

            if (Auth ::user() -> subscribed('main') || Auth ::user() -> subscribed('mains')) {
                $input['status'] = $input['status'];
            } else {
                return redirect() -> route('profile') -> with('error', 'You can not Activate Your Profile because, Your Subscription payment not done yet');
            }
        }
        if (isset($input['playing_age'])) {
            $input['playing_age'] = str_replace(' ', '', trim($input['playing_age']));
        }
        if (isset($input['favourite_directors']) && count($input['favourite_directors'])) {
            $input['favourite_directors'] = json_encode($input['favourite_directors']);
        } else {
            $input['favourite_directors'] = null;
        }
        if (isset($input['top_songs']) && count($input['top_songs'])) {
            $input['top_songs'] = json_encode($input['top_songs']);
        } else {
            $input['top_songs'] = null;
        }
        if (isset($input['top_musicians']) && count($input['top_musicians'])) {
            $input['top_musicians'] = json_encode($input['top_musicians']);
        } else {
            $input['top_musicians'] = null;
        }
        if (isset($input['favourite_films']) && count($input['favourite_films'])) {
            $input['favourite_films'] = json_encode($input['favourite_films']);
        } else {
            $input['favourite_films'] = null;
        }
        if (isset($input['favourite_models']) && count($input['favourite_models'])) {
            $input['favourite_models'] = json_encode($input['favourite_models']);
        } else {
            $input['favourite_models'] = null;
        }
        if (isset($input['favourite_brands']) && count($input['favourite_brands'])) {
            $input['favourite_brands'] = json_encode($input['favourite_brands']);
        } else {
            $input['favourite_brands'] = null;
        }
        if (isset($input['other_professions']) && count($input['other_professions'])) {
            $input['other_professions'] = json_encode($input['other_professions']);
        } else {
            $input['other_professions'] = null;
        }

        if (isset($input['languages'])) {
            $userlanguages = [];
            foreach ($input['languages'] as $key => $value) {
                $language = Languages ::where('language', ucfirst($value)) -> first();
                if (!isset($language)) {
                    Languages ::create(['language' => ucfirst($value)]);
                }
                $userlanguages[] = ucfirst($value);
            }
            $input['languages'] = json_encode($userlanguages);
        }
        if (isset($input['skills'])) {
            $userskills = [];
            foreach ($input['skills'] as $key => $value) {
                $skill = Skills ::where('skill', ucfirst($value)) -> first();
                if (!isset($skill)) {
                    Skills ::create(['skill' => ucfirst($value)]);
                }
                $userskills[] = ucfirst($value);
            }
            $input['skills'] = json_encode($userskills);
        }
        if (isset($input['genre'])) {
            $userGenres = [];
            foreach ($input['genre'] as $key => $value) {
//                $genre = Genres ::where('genre', ucfirst($value)) -> first();
//                if (!isset($genre)) {
//                    Genres ::create(['genre' => ucfirst($value)]);
//                }
                $userGenres[] = ucfirst($value);
            }
            $input['genre'] = json_encode($userGenres);
        }
        if (isset($input['acents'])) {
            $useracents = [];
            foreach ($input['acents'] as $key => $value) {
//                $acents = Accents ::where('accent', ucfirst($value)) -> first();
//                if (!isset($acents)) {
//                    Accents ::create(['accent' => ucfirst($value)]);
//                }
                $useracents[] = ucfirst($value);
            }
            $input['acents'] = json_encode($useracents);
        }
        if (isset($input['role_type'])) {
            $useracents = [];
            foreach ($input['role_type'] as $key => $value) {
                $roletypes = RoleTypes ::where('role', ucfirst($value)) -> first();
                if (!isset($roletypes)) {
                    RoleTypes ::create(['role' => ucfirst($value)]);
                }
                $useracents[] = ucfirst($value);
            }
            $input['role_type'] = json_encode($useracents);
        }
        if (isset($input['instrument'])) {
            $userGenres = [];
            foreach ($input['instrument'] as $key => $value) {
                $userGenres[] = ucfirst($value);
            }
            $input['instrument'] = json_encode($userGenres);
        }

        // $input['stars']=json_encode($input['stars']);
        if (isset($input['eye_colour'])) {
            $input['eye_colour'] = ucfirst($input['eye_colour']);
        }
        if (isset($input['hair_colour'])) {
            $input['hair_colour'] = ucfirst($input['hair_colour']);
        }
        if (!isset($input['representation'])) {
            $input['representation'] = 0;
        }else{
            $input['model_name'] = '';
            $input['model_link'] = '';
        }

        Auth ::user() -> update($input);

        return redirect() -> route('profile') -> with('success', 'Profile Updated Successfully');
    }

    /**
     * Uploads User Images
     *
     * @param Request $request
     * @param \App\Http\Controllers\UserNetworkController $network
     * @return false|string
     */
    public function uploadPhotos(Request $request, UserNetworkController $network)
    {
        $input = $request -> except('_token');

        $galleryCount = PhotoGallery ::where('user_id', Auth ::user() -> id) -> get();

        if (isset($galleryCount) && count($galleryCount) < 8) {

            $photos = $input['photos'];
            $imagesdata = [];
            if (count($galleryCount) + count($input) <= 8) {
                list($type, $input['photos']) = explode(';', $input['photos']);
                list(, $input['photos']) = explode(',', $input['photos']);
                $data = base64_decode($input['photos']);
                $fileName = time() . '.png';
                $path = public_path() . "/img/user_gallery/" . $fileName;
                file_put_contents($path, $data);
                $gallery = PhotoGallery ::create(['user_id' => Auth ::user() -> id, 'image' => $fileName]);
                $imagesdata[$gallery -> id] = $fileName;
                // }
                activity()
                    -> causedBy(Auth ::user())
                    -> performedOn($gallery)
                    -> log('Uploaded a new photo');

                $input['properties'] = json_encode(['imagegallery' => $imagesdata]);
                $input['user_id'] = Auth ::id();
                $feed = UserFeed ::create($input);

                $message = ' Uploaded a new image on their profile';
                if (isset(Auth ::user() -> friendsOfMineNetwork) && count(Auth ::user() -> friendsOfMineNetwork)) {
                    $network -> sendNetworkNotification(Auth ::user() -> friendsOfMineNetwork, $message);
                }
                if (isset(Auth ::user() -> friendOfNetwork) && count(Auth ::user() -> friendOfNetwork)) {
                    $network -> sendNetworkNotification(Auth ::user() -> friendOfNetwork, $message);
                }

                return json_encode(array('status' => 1, 'files' => $imagesdata));
            } else {
                return json_encode(array('status' => 0, 'msg' => 'You can upload 8 images Only'));
            }

        } else {
            return json_encode(array('status' => 0, 'msg' => 'You can upload 8 images Only'));
        }
    }

    /**
     * Delete User Images
     *
     * @param Request $request
     * @return false|string
     */
    public function deletePhotos(Request $request)
    {
        $input = $request -> except('_token');
        $gallery = PhotoGallery ::where('id', $input['id']) -> first();
        $gallery -> delete();
        if (file_exists(public_path('img/user_gallery/') . $gallery -> image)) {
            unlink(public_path('img/user_gallery/') . $gallery -> image);
        }
        return json_encode(array('status' => 1));
    }

    /**
     * Upload User Videos
     *
     * @param Request $request
     * @param \App\Http\Controllers\UserNetworkController $network
     * @return Renderable
     */
    public function uploadVideos(Request $request, UserNetworkController $network)
    {

        $rules = array(
            'video' => 'required|file|max:15000'
        );
        $messages = [
            'video.max' => 'The video has to be less than or equal to 15mb.'
        ];
        $validator = Validator ::make($request -> all(), $rules, $messages);
        if ($validator -> fails()) {

            return response() -> json(array(
                'status' => false,
                'msg' => $validator -> errors() -> all()
            ));
        } else {
            $videoCount = VideoGallery ::where('user_id', Auth ::user() -> id) -> get();

            if (isset($videoCount) && count($videoCount) < 4) {
                $input = $request -> except('_token');

                $video = $input['video'];

                $fileName = time() . $video -> getClientOriginalName();
                $this -> storage -> put($this -> video_upload_path . $fileName, file_get_contents($video -> getRealPath()));
                $videoGallery = VideoGallery ::create(['user_id' => Auth ::user() -> id, 'video' => $fileName]);

                activity()
                    -> causedBy(Auth ::user())
                    -> performedOn($videoGallery)
                    -> log('Uploaded a new Video');


                $input['properties'] = json_encode(['video' => $fileName]);
                $input['user_id'] = Auth ::id();
                $feed = UserFeed ::create($input);

                $message = ' Uploaded a new video on their profile';
                if (isset(Auth ::user() -> friendsOfMineNetwork) && count(Auth ::user() -> friendsOfMineNetwork)) {
                    $network -> sendNetworkNotification(Auth ::user() -> friendsOfMineNetwork, $message);
                }
                if (isset(Auth ::user() -> friendOfNetwork) && count(Auth ::user() -> friendOfNetwork)) {
                    $network -> sendNetworkNotification(Auth ::user() -> friendOfNetwork, $message);
                }

                return json_encode(array('status' => 1, 'msg' => 'Video Uploaded Successfully'));
            } else {
                // return redirect(route('profile') . '#video_gallery')->with('error','You can upload only upload 4 Videos');
                return json_encode(array('status' => 0, 'msg' => 'You can upload only 4 Videos'));
            }
        }
    }

    /**
     * Delete User Video
     *
     * @param Request $request
     * @return Renderable
     */
    public function deleteVideo(Request $request)
    {
        $input = $request -> except('_token');
        $gallery = VideoGallery ::where('id', $input['id']) -> first();
        $gallery -> delete();
        if (file_exists(public_path('img/video_gallery/') . $gallery -> video)) {
            unlink(public_path('img/video_gallery/') . $gallery -> video);
        }
        return json_encode(array('status' => 1));
    }


    public function showNotification()
    {

        $userNotifications = Auth ::user() -> notifications() -> paginate(10);
        return view('user.notification', compact('userNotifications'));
    }

    /**
     * @return RedirectResponse
     */
    public function readNotification()
    {
        $notification = auth() -> user() -> unreadNotifications;
        foreach ($notification as $key => $value) {
            // if(snake_case(class_basename($value->type))=='message_notification' && $value->notifiable_id==Auth::user()->id)
            $id = $value -> id;
            auth() -> user() -> unreadNotifications -> where('id', $id) -> markAsRead();
        }
        return redirect() -> route('show.all.notification');
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function markAsReadNotification(Request $request)
    {
        $id = $request -> all()['id'];
        auth() -> user() -> unreadNotifications -> where('id', $id) -> markAsRead();
        return json_encode(array('status' => true));
    }

    /**
     * @param Request $request
     * @return false|string
     */
    public function increasePersonality(Request $request)
    {
        $input = $request -> all();
        $personality = UsersPersonalityTraits ::where('user_id', $input['userid']) -> first();
        $pruser = User ::find($input['userid']);
        if (isset($personality)) {
            if ($personality -> click_by != '') {

                $oldClickedUser = json_decode($personality -> click_by, true);

                if (isset($oldClickedUser[Auth ::id()])) {

                    $userClickTraits = $oldClickedUser[Auth ::id()];
                    if (!in_array($input['traits'], $userClickTraits)) {

                        $traitsCount = $personality[$input['traits']] + 1;
                        $oldClickedUser[Auth ::id()][] = $input['traits'];
                        $click_by = json_encode($oldClickedUser);
                        $personality -> update([
                            $input['traits'] => $traitsCount,
                            'click_by' => $click_by
                        ]);
                    }
//                    $msg = Auth ::user() -> first_name . ' ' . Auth ::user() -> last_name . ' Improved Your ' . getPersonalityTraits($input['traits']) . ' Personality Traits';
//                    $pruser -> notify(new PersonalityTraits($msg));
//
//                    activity()
//                        -> causedBy($pruser)
//                        -> performedOn($personality)
//                        -> withProperties(['name' => Auth ::user() -> first_name . ' ' . Auth ::user() -> last_name, 'username' => Auth ::user() -> username])
//                        -> log('Improved Your ' . getPersonalityTraits($input['traits']) . ' Personality');

                    return json_encode(array('status' => 1, 'personality' => $personality[$input['traits']]));
                } else {
                    $traitsCount = $personality[$input['traits']] + 1;
                    $oldClickedUser[Auth ::id()][] = $input['traits'];
                    $click_by = json_encode($oldClickedUser);
                    $personality -> update([
                        $input['traits'] => $traitsCount,
                        'click_by' => $click_by
                    ]);
                    $msg = Auth ::user() -> first_name . ' ' . Auth ::user() -> last_name . ' has Improved Your Personality Traits';
                    $pruser -> notify(new PersonalityTraits($msg));

                    activity()
                        -> causedBy($pruser)
                        -> performedOn($personality)
                        -> withProperties(['name' => Auth ::user() -> first_name . ' ' . Auth ::user() -> last_name, 'username' => Auth ::user() -> username])
                        -> log('has Improved Your Personality Traits');

                    return json_encode(array('status' => 1, 'personality' => $personality[$input['traits']]));
                }
            } else {
                $traitsCount = $personality[$input['traits']] + 1;
                $click_by = json_encode(array(Auth ::id() => array($input['traits'])));
                $personality -> update([
                    $input['traits'] => $traitsCount,
                    'click_by' => $click_by
                ]);
            }
            $msg = Auth ::user() -> first_name . ' ' . Auth ::user() -> last_name . ' has Improved Your Personality Traits';
            $pruser -> notify(new PersonalityTraits($msg));
            activity()
                -> causedBy($pruser)
                -> performedOn($personality)
                -> withProperties(['name' => Auth ::user() -> first_name . ' ' . Auth ::user() -> last_name, 'username' => Auth ::user() -> username])
                -> log('has Improved Your Personality Traits');
            return json_encode(array('status' => 1, 'personality' => $personality[$input['traits']]));
        } else {
            return json_encode(array('status' => 0, 'msg' => 'Personality Not Found'));
        }

    }

    /**
     * @param Request $request
     * @return false|string
     */
    public function getCountry(Request $request)
    {
        $countries = Country ::where('name', 'like', '%' . $request -> all()['search'] . '%') -> pluck('name', 'id') -> toArray();
        $countriesDdata = [];
        foreach ($countries as $key => $country) {

            $countriesDdata[] = array("id" => $country, "text" => $country);
        }

        return json_encode($countriesDdata);
    }

    /**
     * @return View
     */
    public function imagegallery()
    {
        return view('user.gallery');
    }

    /**
     * @return View
     */
    public function videogallery()
    {
        return view('user.video');
    }

    /**
     * @return JsonResponse
     */
    public function collectReward()
    {
        $alreadyCollectPointsToday = Like ::where('like_user_type', 'reward_point') -> where('profile_id', Auth ::user() -> id) -> where('created_at', 'like', '%' . date('Y-m-d') . '%') -> first();
        if (isset($alreadyCollectPointsToday)) {
            return response() -> json(array('status' => 0, 'msg' => 'You have already collected your Daily Reward for today. Come back tomorrow for your new Reward!'));
        } else {
            $ran = array(1, 2, 3);
            $randomPoint = $ran[array_rand($ran, 1)];
            $ip = request() -> ip();
            Like ::create([
                'user_id' => Auth ::user() -> id,
                'profile_id' => Auth ::user() -> id,
                'like_user_type' => 'reward_point',
                'ip_address' => $ip,
                'count' => $randomPoint
            ]);
            return response() -> json(array('status' => 1, 'randomPoint' => $randomPoint, 'msg' => 'Reward Point Added Successfully'));

        }
    }

    /**
     * @return Factory|View
     * @throws Stripe\Exception\ApiErrorException
     */
    public function getMembershipPlan()
    {
        Stripe\Stripe ::setApiKey(config('services.stripe.secret'));
        $plans = Stripe\Plan ::all();
        $adminPlans = MembershipSubscriptionPlan ::all();
        return view('user.change-membership-plan', compact('plans', 'adminPlans'));
    }

    /**
     * @param $plan_id
     * @return RedirectResponse
     */
    public function changeMembershipPlan($plan_id)
    {
        $user = Auth ::user();
        $user -> subscription('main') -> swap($plan_id);
        return redirect() -> route('dashboard') -> with('success', 'Membership Updated Successfully.');
    }

    /**
     * Delete User
     * @param Request $request
     * @return RedirectResponse
     */
    public function deleteUserProfile(Request $request)
    {
        $input = $request -> all();
        $user = User ::find($input['id']);
        $user -> delete();

        return response() -> json(['status' => 1], 200);
    }
}
