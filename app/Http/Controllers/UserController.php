<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Models\Like\Like;
use App\Models\Genres\Genres;
use App\Models\Accents\Accents;
use App\Models\UserNetwork\UserNetwork;
use App\Models\Contactinquire\Contactinquire;
use App\Mail\ContactProfileEmail;
use App\Notifications\LikeNotification;
use App\Notifications\ContactProfileNotification;
use App\User;
use Carbon\Carbon;
use Auth;
use Illuminate\View\View;
use Throwable as ThrowableAlias;
use Validator;
use DB;
use Session;

class UserController extends Controller
{
    /**
     * Associated Repository Model.
     */
    protected $upload_path;
    protected $storage;

    /**
     *
     */
    public function __construct(User $model)
    {
        $this -> model = $model;

        $this -> upload_path = 'img' . DIRECTORY_SEPARATOR . 'inquiry' . DIRECTORY_SEPARATOR;

        $this -> storage = Storage ::disk('public');
    }

    /**
     * Show the Profiles.
     *
     * @param Request $request
     * @return array|Renderable|string
     * @throws ThrowableAlias
     */
    public function index(Request $request)
    {
        $input = $request -> all();

        if ($request -> ajax()) {
            $query = User ::query();
            if (Auth ::user()) {
                $query = $query -> Where('users.id', '!=', Auth ::user() -> id) -> Where('users.status', 1) -> Where('users.private_user', 0);
            }

            if (isset($input['usertype'])) {
                if ($input['usertype'] == 'all') {
                    // $query = $query->Where('users.user_type', '!=','Crew');
                    $query = $query -> Where('users.status', 1) -> Where('users.private_user', 0);
                } else {
                    $query = $query -> Where('users.status', 1) -> Where('users.private_user', 0) -> Where('users.user_type', getUserType($input['usertype']));
                }
                $request -> session() -> put('select_user_type', $input['usertype']);
            }
            if ($request -> session() -> has('select_user_type')) {
                $utype = $request -> session() -> get('select_user_type');
                if ($utype == 'all') {
                    // $query = $query->Where('users.user_type', '!=','Crew');
                    $query = $query -> Where('users.status', 1) -> Where('users.private_user', 0);
                } else {
                    $query = $query -> Where('users.status', 1) -> Where('users.private_user', 0) -> Where('users.user_type', getUserType($utype));
                }
            }
            if (isset($input['country']) && $input['country'] != '') {
                $query = $query -> Where('users.country', 'LIKE', '%' . $input['country'] . '%');
            }
            if (isset($input['age']) && $input['age'] != '') {
                $query = $query -> Where('users.playing_age', 'LIKE', '%' . $input['age'] . '%');
            }
            if (isset($input['gender']) && $input['gender'] != '') {
                $query = $query -> Where('users.gender', $input['gender']);
            }
            if (isset($input['genre']) && $input['genre'] != '') {
                $query = $query -> Where('users.genre', 'LIKE', '%' . $input['genre'] . '%');
            }
            if (isset($input['acent']) && $input['acent'] != '') {
                $query = $query -> Where('users.acents', 'LIKE', '%' . $input['acent'] . '%');
            }
            if (isset($input['height']) && $input['height'] != '') {
                $query = $query -> Where('users.height', 'LIKE', '%' . $input['height'] . '%');
            }
            if (isset($input['eye_colour']) && $input['eye_colour'] != '') {
                $query = $query -> Where('users.eye_colour', 'LIKE', '%' . $input['eye_colour'] . '%');
            }
            if (isset($input['hair_colour']) && $input['hair_colour'] != '') {
                $query = $query -> Where('users.hair_colour', 'LIKE', '%' . $input['hair_colour'] . '%');
            }
            if (isset($input['instrument']) && $input['instrument'] != '') {
                $query = $query -> Where('users.instrument', 'LIKE', '%' . $input['instrument'] . '%');
            }
            if (isset($input['crew_type']) && $input['crew_type'] != '') {
                $query = $query -> Where('users.status', 1)
                    -> Where('users.private_user', 0)
                    -> Where('users.crew_type', 'LIKE', '%' . $input['crew_type'] . '%')
                    -> orWhere('users.other_professions', 'LIKE', '%' . $input['crew_type'] . '%');
            }

            //find with profession
            if ($request -> session() -> has('select_user_type')) {
                $utype = $request -> session() -> get('select_user_type');
                $input['usertype'] = $utype;
            }
            if (isset($input['search'])) {

                $search = $input['search'];
                if (Auth ::user()) {
                    $query = $query -> Where('users.status', 1) -> Where('users.private_user', 0) -> Where('users.id', '!=', Auth ::user() -> id);
                }
                $query -> where(function ($fq) use ($search, $input) {

                    $fq -> orWhere('users.username', 'LIKE', '%' . explode(' ', $search)[0] . '%');
                    $fq -> orWhere('users.first_name', 'LIKE', '%' . $search . '%');
                    $fq -> orWhere('users.last_name', 'LIKE', '%' . $search . '%');
                    if (getUserType($input['usertype']) == '4') {
                        $fq -> orWhere('users.other_professions', 'LIKE', '%Choreographer%')
                            -> orWhere('users.other_professions', 'LIKE', '%Artist%')
                            -> orWhere('users.other_professions', 'LIKE', '%Cinematographer%')
                            -> orWhere('users.other_professions', 'LIKE', '%Composer%')
                            -> orWhere('users.other_professions', 'LIKE', '%Director%')
                            -> orWhere('users.other_professions', 'LIKE', '%Editor%')
                            -> orWhere('users.other_professions', 'LIKE', '%Make Up Artist%')
                            -> orWhere('users.other_professions', 'LIKE', '%Photographer%')
                            -> orWhere('users.other_professions', 'LIKE', '%Sound, Light, Effects, Design%')
                            -> orWhere('users.other_professions', 'LIKE', '%Writer%');

                        if (isset($input['country']) && $input['country'] != '') {
                            $fq -> Where('users.country', 'LIKE', '%' . $input['country'] . '%');
                        }
                        if (isset($input['age']) && $input['age'] != '') {
                            $fq -> Where('users.playing_age', 'LIKE', '%' . $input['age'] . '%');
                        }
                        if (isset($input['gender']) && $input['gender'] != '') {
                            $fq -> Where('users.gender', $input['gender']);
                        }
                        if (isset($input['genre']) && $input['genre'] != '') {
                            $fq -> Where('users.genre', 'LIKE', '%' . $input['genre'] . '%');
                        }
                        if (isset($input['acent']) && $input['acent'] != '') {
                            $fq -> Where('users.acents', 'LIKE', '%' . $input['acent'] . '%');
                        }
                        if (isset($input['height']) && $input['height'] != '') {
                            $fq -> Where('users.height', 'LIKE', '%' . $input['height'] . '%');
                        }
                        if (isset($input['eye_colour']) && $input['eye_colour'] != '') {
                            $fq -> Where('users.eye_colour', 'LIKE', '%' . $input['eye_colour'] . '%');
                        }
                        if (isset($input['hair_colour']) && $input['hair_colour'] != '') {
                            $fq -> Where('users.hair_colour', 'LIKE', '%' . $input['hair_colour'] . '%');
                        }
                        if (isset($input['instrument']) && $input['instrument'] != '') {
                            $fq -> Where('users.instrument', 'LIKE', '%' . $input['instrument'] . '%');
                        }
                        if (isset($input['crew_type']) && $input['crew_type'] != '') {
                            $fq -> Where('users.status', 1)
                                -> Where('users.private_user', 0)
                                -> Where('users.crew_type', 'LIKE', '%' . $input['crew_type'] . '%');
                        }
                    } else {
                        $fq -> orWhere('users.other_professions', 'LIKE', '%' . $input['usertype'] . '%');
                        if (isset($input['country']) && $input['country'] != '') {
                            $fq -> Where('users.country', 'LIKE', '%' . $input['country'] . '%');
                        }
                        if (isset($input['age']) && $input['age'] != '') {
                            $fq -> Where('users.playing_age', 'LIKE', '%' . $input['age'] . '%');
                        }
                        if (isset($input['gender']) && $input['gender'] != '') {
                            $fq -> Where('users.gender', $input['gender']);
                        }
                        if (isset($input['genre']) && $input['genre'] != '') {
                            $fq -> Where('users.genre', 'LIKE', '%' . $input['genre'] . '%');
                        }
                        if (isset($input['acent']) && $input['acent'] != '') {
                            $fq -> Where('users.acents', 'LIKE', '%' . $input['acent'] . '%');
                        }
                        if (isset($input['height']) && $input['height'] != '') {
                            $fq -> Where('users.height', 'LIKE', '%' . $input['height'] . '%');
                        }
                        if (isset($input['eye_colour']) && $input['eye_colour'] != '') {
                            $fq -> Where('users.eye_colour', 'LIKE', '%' . $input['eye_colour'] . '%');
                        }
                        if (isset($input['hair_colour']) && $input['hair_colour'] != '') {
                            $fq -> Where('users.hair_colour', 'LIKE', '%' . $input['hair_colour'] . '%');
                        }
                        if (isset($input['instrument']) && $input['instrument'] != '') {
                            $fq -> Where('users.instrument', 'LIKE', '%' . $input['instrument'] . '%');
                        }
                        if (isset($input['crew_type']) && $input['crew_type'] != '') {
                            $fq -> Where('users.status', 1)
                                -> Where('users.private_user', 0)
                                -> Where('users.crew_type', 'LIKE', '%' . $input['crew_type'] . '%')
                                -> orWhere('users.other_professions', 'LIKE', '%' . $input['crew_type'] . '%');
                        }
                    }
                });
            } else {

                if (isset($input['usertype'])) {
                    if (getUserType($input['usertype']) == '4') {
                        $query -> orwhere(function ($fq) use ($input) {

                            $fq -> orWhere('users.other_professions', 'LIKE', '%Choreographer%')
                                -> orWhere('users.other_professions', 'LIKE', '%Artist%')
                                -> orWhere('users.other_professions', 'LIKE', '%Cinematographer%')
                                -> orWhere('users.other_professions', 'LIKE', '%Composer%')
                                -> orWhere('users.other_professions', 'LIKE', '%Director%')
                                -> orWhere('users.other_professions', 'LIKE', '%Editor%')
                                -> orWhere('users.other_professions', 'LIKE', '%Make Up Artist%')
                                -> orWhere('users.other_professions', 'LIKE', '%Photographer%')
                                -> orWhere('users.other_professions', 'LIKE', '%Sound, Light, Effects, Design%')
                                -> orWhere('users.other_professions', 'LIKE', '%Writer%');
                            if (isset($input['country']) && $input['country'] != '') {
                                $fq -> Where('users.country', 'LIKE', '%' . $input['country'] . '%');
                            }
                            if (isset($input['age']) && $input['age'] != '') {
                                $fq -> Where('users.playing_age', 'LIKE', '%' . $input['age'] . '%');
                            }
                            if (isset($input['gender']) && $input['gender'] != '') {
                                $fq -> Where('users.gender', $input['gender']);
                            }
                            if (isset($input['genre']) && $input['genre'] != '') {
                                $fq -> Where('users.genre', 'LIKE', '%' . $input['genre'] . '%');
                            }
                            if (isset($input['acent']) && $input['acent'] != '') {
                                $fq -> Where('users.acents', 'LIKE', '%' . $input['acent'] . '%');
                            }
                            if (isset($input['height']) && $input['height'] != '') {
                                $fq -> Where('users.height', 'LIKE', '%' . $input['height'] . '%');
                            }
                            if (isset($input['eye_colour']) && $input['eye_colour'] != '') {
                                $fq -> Where('users.eye_colour', 'LIKE', '%' . $input['eye_colour'] . '%');
                            }
                            if (isset($input['hair_colour']) && $input['hair_colour'] != '') {
                                $fq -> Where('users.hair_colour', 'LIKE', '%' . $input['hair_colour'] . '%');
                            }
                            if (isset($input['instrument']) && $input['instrument'] != '') {
                                $fq -> Where('users.instrument', 'LIKE', '%' . $input['instrument'] . '%');
                            }
                            if (isset($input['crew_type']) && $input['crew_type'] != '') {
                                $fq -> Where('users.status', 1)
                                    -> Where('users.private_user', 0)
                                    -> Where('users.crew_type', 'LIKE', '%' . $input['crew_type'] . '%')
                                    -> orWhere('users.other_professions', 'LIKE', '%' . $input['crew_type'] . '%');
                            }
                        });
                    } else {
                        $query -> orwhere(function ($fq) use ($input) {
                            $fq -> orWhere('users.other_professions', 'LIKE', '%' . $input['usertype'] . '%');
                            if (isset($input['country']) && $input['country'] != '') {
                                $fq -> Where('users.country', 'LIKE', '%' . $input['country'] . '%');
                            }
                            if (isset($input['age']) && $input['age'] != '') {
                                $fq -> Where('users.playing_age', 'LIKE', '%' . $input['age'] . '%');
                            }
                            if (isset($input['gender']) && $input['gender'] != '') {
                                $fq -> Where('users.gender', $input['gender']);
                            }
                            if (isset($input['genre']) && $input['genre'] != '') {
                                $fq -> Where('users.genre', 'LIKE', '%' . $input['genre'] . '%');
                            }
                            if (isset($input['acent']) && $input['acent'] != '') {
                                $fq -> Where('users.acents', 'LIKE', '%' . $input['acent'] . '%');
                            }
                            if (isset($input['height']) && $input['height'] != '') {
                                $fq -> Where('users.height', 'LIKE', '%' . $input['height'] . '%');
                            }
                            if (isset($input['eye_colour']) && $input['eye_colour'] != '') {
                                $fq -> Where('users.eye_colour', 'LIKE', '%' . $input['eye_colour'] . '%');
                            }
                            if (isset($input['hair_colour']) && $input['hair_colour'] != '') {
                                $fq -> Where('users.hair_colour', 'LIKE', '%' . $input['hair_colour'] . '%');
                            }
                            if (isset($input['instrument']) && $input['instrument'] != '') {
                                $fq -> Where('users.instrument', 'LIKE', '%' . $input['instrument'] . '%');
                            }
                            if (isset($input['crew_type']) && $input['crew_type'] != '') {
                                $fq -> Where('users.status', 1)
                                    -> Where('users.private_user', 0)
                                    -> Where('users.crew_type', 'LIKE', '%' . $input['crew_type'] . '%')
                                    -> orWhere('users.other_professions', 'LIKE', '%' . $input['crew_type'] . '%');
                            }
                        });
                    }
                }
            }
            //end find with profession


            $searchResult = $query -> leftjoin('subscriptions as s', 'users.id', '=', 's.user_id') -> where('s.stripe_status', 'active') -> Where('users.status', 1) -> Where('users.private_user', 0) -> select("users.*",
                DB ::raw("(SELECT SUM(likes.count) FROM likes WHERE likes.profile_id=users.id) as likes"))
                -> orderby('likes', 'DESC')
                -> paginate(30);
            if (count($searchResult) == 0) {
                return view('profile.ajaxblanckprofles', compact('input')) -> render();
            } else {
                return view('profile.ajaxprofiles', compact('searchResult')) -> render();
            }
        } else {

            $query = User ::query();
            if (Auth ::user()) {
                // $query = $query->Where('users.id', '!=',Auth::user()->id)->Where('users.user_type', '!=','Crew');
                $query = $query -> Where('users.id', '!=', Auth ::user() -> id);
            }
            $searchResult = $query -> leftjoin('subscriptions as s', 'users.id', '=', 's.user_id') -> where('s.stripe_status', 'active') -> Where('users.status', 1) -> Where('users.private_user', 0) -> select("users.*",
                DB ::raw("(SELECT SUM(likes.count)  FROM likes WHERE likes.profile_id=users.id) as likes"))
                -> orderby('likes', 'DESC')
                -> paginate(30);

            $count = count($searchResult);
            return view('profile.profiles', compact('input', 'searchResult', 'count'));
        }
    }

    /**
     * @return JsonResponse
     */
    public function getSearchOptions()
    {
        $userdata = User ::groupBy('country') -> whereNotNull('country') -> pluck('country');
        $playing_age = User ::groupBy('playing_age') -> whereNotNull('playing_age') -> pluck('playing_age');
//        $heights = User ::groupBy('height') -> whereNotNull('height') -> pluck('height');
        $eye_colours = User ::groupBy('eye_colour') -> whereNotNull('eye_colour') -> pluck('eye_colour');
//        $hair_colours = User ::groupBy('hair_colour') -> whereNotNull('hair_colour') -> pluck('hair_colour');
//        $instruments = User ::groupBy('instrument') -> whereNotNull('instrument') -> pluck('instrument');
//        $gender = User ::groupBy('gender') -> whereNotNull('gender') -> pluck('gender');
//        $genre = Genres ::groupBy('genre') -> whereNotNull('genre') -> pluck('genre');
//        $acents = Accents ::groupBy('accent') -> whereNotNull('accent') -> pluck('accent');

        return response() -> json([
            'userdata' => $userdata,
            'playing_age' => $playing_age,
//            'gender' => $gender,
//            'genre' => $genre,
//            'acents' => $acents,
//            'heights' => $heights,
            'eye_colours' => $eye_colours,
//            'hair_colours' => $hair_colours,
//            'instruments' => $instruments,
        ]);
    }

    /**
     * @param $username
     * @return Factory|RedirectResponse|View
     */
    public function userProfile($username)
    {
        $slug = str_replace('-', ' ', $username);

        $user = User ::where('username', $slug) -> first();
        if (!isset($user)) {
            return redirect() -> back() -> with('error', 'User No longer Available');
        }
        if (Auth ::user()) {
            $id = $user -> id;
            $userFriend = UserNetwork ::where(['user_id' => $id, 'friend_id' => Auth ::user() -> id])
                -> orWhere(function ($query) use ($id) {
                    $query -> where(['user_id' => Auth ::user() -> id, 'friend_id' => $id]);
                }) -> first();
        } else {
            $userFriend = [];
        }

        $ip = request() -> ip();
        $likeusers = [];
        if (Auth ::user()) {
            $liveuser = Auth ::user() -> id;
            $likeusers = Like ::where('user_id', $liveuser) -> Where('profile_id', $user -> id) -> Where('like_user_type', $user -> user_type) -> where('created_at', '>', date('Y-m-d')) -> first();
        }
        return view('profile.profle-details', compact('user', 'likeusers', 'userFriend'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function contactForm(Request $request)
    {
        $input = $request -> all();
        $rules = array(
            'name' => 'required',
            'company' => 'required',
            'subject' => 'required',
            'email' => 'required|email',
            'message' => 'required',
        );
        if (isset($input['instagram']) && $input['instagram'] != '') {
            $rules['instagram'] = 'url';
        }
        if (isset($input['facebook']) && $input['facebook'] != '') {
            $rules['facebook'] = 'url';
        }
        if (isset($input['linkedin']) && $input['linkedin'] != '') {
            $rules['linkedin'] = 'url';
        }
        $validator = Validator ::make($request -> all(), $rules);
        if ($validator -> fails()) {
            return response() -> json(array(
                'status' => false,
                'msg' => $validator -> errors()
            ));
        } else {

            $touser = User ::find($input['to_user']);
            // Mail::to($touser->email)->send(new ContactProfileEmail($input,$touser));
            $msg = $input['name'] . ' Sent an Inquiry for your profile';

            $touser -> notify((new ContactProfileNotification($touser, trim($msg))));
            $input['user_id'] = $input['to_user'];
            $fileName = '';
            if (isset($input['photo']) && !empty($input['photo'])) {
                $image = $input['photo'];

                $fileName = time() . $image -> getClientOriginalName();

                $this -> storage -> put($this -> upload_path . $fileName, file_get_contents($image -> getRealPath()));
                $input['photo'] = $fileName;
            }

            Contactinquire ::create($input);

            return response() -> json(array(
                'status' => true,
                'msg' => 'Email Send Successfully'
            ));
        }
    }

    public function deleteContactInquires($id)
    {
        Contactinquire ::where('id', $id) -> delete();
        return redirect() -> back() -> with('success', 'Contact Inquire Deleted Successfully !!');
    }

    public function deleteNotification($id)
    {
        DB ::table('notifications') -> where('id', $id) -> delete();
        return redirect() -> back() -> with('success', 'Notifications Deleted Successfully !!');
    }

    public function unlikeProfile(Request $request)
    {
        $input = $request -> all();
        $user_id = Auth ::user() -> id;
        $userlikes = Like ::where('user_id', $user_id) -> Where('profile_id', $input['id']) -> where('created_at', 'like', '%' . date('Y-m-d') . '%') -> first();
        if (isset($userlikes)) {
            $userlikes -> delete();
            $likecount = likeCount($input['id']);
            echo json_encode(array('status' => 1, 'likecount' => $likecount));
        } else {
            echo json_encode(array('status' => 0));
        }
    }

    public function likeProfile(Request $request)
    {
        $input = $request -> all();
        $user_id = Auth ::user() -> id;
        $likeUser = User ::find($input['id']);
        $ip = request() -> ip();

        $userTypeLikes = Like ::where('user_id', $user_id) -> Where('like_user_type', $likeUser -> user_type) -> where('created_at', 'like', '%' . date('Y-m-d') . '%') -> first();

        if (!isset($userTypeLikes)) {
            $like = likeProfile($user_id, $input['id'], $ip, $likeUser -> user_type);

            $likecount = likeCount($input['id']);
            $msg = '<a href="' . url('profile-details/' . Auth ::user() -> username) . '">' . ucfirst(Auth ::user() -> first_name) . ' ' . ucfirst(Auth ::user() -> last_name) . '  Liked your profile and you received 1 star to your profile </a>';
//            $name = ucfirst(Auth ::user() -> first_name) . ' ' . ucfirst(Auth ::user() -> last_name) .' Liked your profile and you received 1 star to your profile';
            $likeUser -> notify(new LikeNotification($msg));

            activity()
                -> causedBy($likeUser)
                -> performedOn($like)
                -> withProperties(['name' => ucfirst(Auth ::user() -> first_name) . ' ' . ucfirst(Auth ::user() -> last_name), 'username' => Auth ::user() -> username])
                -> log(' Liked your profile and you received 1 star to your profile');

            echo json_encode(array('status' => 1, 'likecount' => $likecount));
        } else {
            echo json_encode(array('status' => 0, 'msg' => 'You Liked One ' . getUserTypeValue($likeUser -> user_type) . ' User Already, Please Try Tomorrow.'));
        }

    }

    public function contactinquires()
    {
        $user_id = Auth ::user() -> id;
        $contactinquires = Contactinquire ::where('user_id', $user_id) -> orderBy('id', 'DESC') -> paginate(10);
        return view('user.contactinquire', compact('contactinquires'));
    }
}
