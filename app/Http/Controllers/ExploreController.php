<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Explore\Explore;
use App\Models\ExploreRequest\ExploreRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\ExploreRequestMail;
use Auth;
use App\Http\Controllers\UserNetworkController;
use App\Models\UserFeed\UserFeed;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;

class ExploreController extends Controller
{
    /**
     * @var string
     */
    protected $upload_path;
    protected $storage;

    /**
     * ExploreController constructor.
     */
    public function __construct()
    {
        $this -> upload_path = 'documents' . DIRECTORY_SEPARATOR . 'explore_cv' . DIRECTORY_SEPARATOR;

        $this -> storage = Storage ::disk('public');
    }

    /**
     * @param Request $request
     * @return array|View|string
     * @throws Throwable
     */
    public function index(Request $request)
    {
        $input = $request -> all();
        $query = Explore ::query();
        if ($request -> ajax()) {
            if (isset($input['search']) && $input['search'] != '') {

                $search = $input['search'];
                $query -> where(function ($fq) use ($search) {
                    $fq -> orWhere('title', 'LIKE', '%' . explode(' ', $search)[0] . '%');
                    $fq -> orWhere('description', 'LIKE', '%' . $search . '%');
                });

            }
            if (isset($input['country']) && $input['country'] != '') {
                $query = $query -> Where('country', 'LIKE', '%' . $input['country'] . '%');
            }
            if (isset($input['usertype'])) {
                if ($input['usertype'] == 'all') {
                    $query = $query;
                } else {
                    $query = $query -> Where('job_type', $input['usertype']);
                }
                $request -> session() -> put('select_user_type', $input['usertype']);
            }
            if ($request -> session() -> has('select_user_type')) {
                $utype = $request -> session() -> get('select_user_type');

                if ($utype == 'all') {
                    // $query = $query->Where('users.user_type', '!=','Crew');
                    $query = $query;
                } else {
                    $query = $query -> Where('job_type', $utype);
                }
            }
            $explore = $query -> orderby('created_at', 'DESC') -> where('status', 1) -> paginate(25);
            if (count($explore) == 0) {
                return view('explore.ajaxblanckdevelop', compact('input')) -> render();
            } else {
                return view('explore.ajax-develop', compact('explore')) -> render();
            }
        } else {
            $explore = $query -> orderby('created_at', 'DESC') -> where('status', 1) -> paginate(25);
            return view('explore.explore', compact('explore'));
        }
    }

    /**
     * @return JsonResponse
     */
    public function getDevelopSearchOptions()
    {
        $country = Explore ::groupBy('country') -> whereNotNull('country') -> pluck('country');
        return response() -> json([
            'country' => $country,
        ]);
    }

    /**
     * @param $slug
     * @return RedirectResponse|View
     */
    public function applicationForm($slug)
    {
        $explore = Explore ::Where('slug', $slug) -> first();
        if (isset($explore)) {
            return view('explore.explore-form', compact('explore'));
        } else {
            return redirect() -> back() -> with('error', 'Develop is not Available');
        }
    }

    /**
     * @param Request $request
     * @param \App\Http\Controllers\UserNetworkController $network
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function sendApplication(Request $request, UserNetworkController $network)
    {
        $this -> validate($request, [
            'name' => 'required',
            'phone' => 'required|numeric',
            'cover_letter' => 'required',
        ], [
            'name.required' => 'The Name field is required',
            'phone.required' => 'The Contact number field is required',
            'phone.numeric' => 'The Contact number must be Numeric',
            'cover_letter.required' => 'The Message field is required',
        ]);

        $input = $request -> except('_token');
        $setting = settings();
        if (isset($setting[0] -> email) && $setting[0] -> email) {
            if (Auth ::user() -> status == 1) {
                $input['profile_name'] = Auth ::user() -> first_name . ' ' . Auth ::user() -> last_name;
                $input['email'] = Auth ::user() -> email;
                $input['user_id'] = Auth ::user() -> id;
                $explore = ExploreRequest ::create($input);
                $this -> sendExploreRequestMail($input);

                activity()
                    -> causedBy(Auth ::user())
                    -> performedOn($explore)
                    -> withProperties(['explore' => $explore -> explore -> slug])
                    -> log('Sent an Inquiry on');

                $input['properties'] = json_encode(['develop' => ['develop' => $explore -> explore -> slug]]);
                $input['user_id'] = Auth ::id();
                $feed = UserFeed ::create($input);

                $message = ' Sent an Inquiry';
                if (isset(Auth ::user() -> friendsOfMineNetwork) && count(Auth ::user() -> friendsOfMineNetwork)) {
                    $network -> sendNetworkNotification(Auth ::user() -> friendsOfMineNetwork, $message);
                }
                if (isset(Auth ::user() -> friendOfNetwork) && count(Auth ::user() -> friendOfNetwork)) {
                    $network -> sendNetworkNotification(Auth ::user() -> friendOfNetwork, $message);
                }

                return redirect() -> back() -> with('success', 'Develop - Requested Successfully');
            } else {
                return redirect() -> back() -> with('error', 'You have not Permission to Request');
            }
        } else {
            return redirect() -> back() -> with('error', 'Error, Please Contact Us');
        }
    }

    public function sendExploreRequestMail($userdata)
    {
        // Send mail to User
        $setting = settings();
        Mail ::to($setting[0] -> email) -> send(new ExploreRequestMail($userdata));
    }

    public function uploadImage($input)
    {
        if (isset($input['cv']) && !empty($input['cv'])) {
            $cv = $input['cv'];

            $fileName = time() . $cv -> getClientOriginalName();

            $this -> storage -> put($this -> upload_path . $fileName, file_get_contents($cv -> getRealPath()));

            $input['cv'] = $fileName;
            return $input;
        }
    }


}
