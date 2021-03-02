<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Models\Discovers\Discovers;
use App\Models\DiscoversRequests\DiscoversRequests;
use App\Mail\DiscoverRequestMail;
use Auth;
use App\Http\Controllers\UserNetworkController;
use App\Models\UserFeed\UserFeed;

class DiscoverController extends Controller
{
    protected $upload_path;
    protected $storage;

    public function __construct()
    {
        $this -> upload_path = 'documents' . DIRECTORY_SEPARATOR . 'discover_cv' . DIRECTORY_SEPARATOR;

        $this -> storage = Storage ::disk('public');
    }

    public function index(Request $request)
    {
        $input = $request -> all();
        $query = Discovers ::query();
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
                    $query = $query -> Where('competitions', $input['usertype']);
                }
                $request -> session() -> put('select_user_type', $input['usertype']);
            }
            if ($request -> session() -> has('select_user_type')) {
                $utype = $request -> session() -> get('select_user_type');
                if ($utype == 'all') {
                    $query = $query;
                } else {
                    $query = $query -> Where('competitions', $utype);
                }
            }
            $discovers = $query -> orderby('created_at', 'DESC') -> where('status', 1) -> orWhere('status', 2) -> paginate(25);
            if (count($discovers) == 0) {
                return view('discover.ajaxblanckdiscover', compact('input')) -> render();
            } else {
                return view('discover.ajax-discover', compact('discovers')) -> render();
            }
        } else {
            $discovers = $query -> orderby('created_at', 'DESC') -> where('status', 1) -> orWhere('status', 2) -> paginate(25);
            return view('discover.discover', compact('discovers'));
        }
    }

    public function getCareerSearchOptions()
    {
        $country = Discovers ::groupBy('country') -> whereNotNull('country') -> pluck('country');
        return response() -> json([
            'country' => $country,
        ]);
    }

    public function applicationForm($title)
    {
        $title = str_replace('-', ' ', $title);

        $discover = Discovers ::Where('title', $title) -> first();
        if (isset($discover)) {
            return view('discover.discover-form', compact('discover'));
        } else {
            return redirect() -> back() -> with('error', 'Competition is not Available');
        }


    }

    public function sendApplication(Request $request, UserNetworkController $network)
    {
        $this -> validate($request, [
            'cover_letter' => 'required',
            'cv' => 'required|mimes:pdf,docx'
        ], [
            'cover_letter.required' => 'Please add Additional Comments',
            'cv.required' => 'Please Enter your Work',
            'cv.mimes' => 'File must be pdf,docx'
        ]);

        $input = $request -> except('_token');
        $setting = settings();
        if (isset($setting[0] -> email) && $setting[0] -> email) {
            if (Auth ::user() -> status == 1) {
                // Uploading Cv
                if (array_key_exists('cv', $input) && !empty($input['cv'])) {
                    $input = $this -> uploadImage($input);
                }
                $input['profile_name'] = Auth ::user() -> first_name . ' ' . Auth ::user() -> last_name;
                $input['email'] = Auth ::user() -> email;
                $input['user_id'] = Auth ::user() -> id;
                $enter = DiscoversRequests ::create($input);
                $this -> sendDiscoverRequestMail($input);

                activity()
                    -> causedBy(Auth ::user())
                    -> performedOn($enter)
                    -> withProperties(['enter' => $enter -> discovers -> title])
                    -> log('Apply on Enter');

                $input['properties'] = json_encode(['enter' => ['enter' => $enter -> discovers -> title]]);
                $input['user_id'] = Auth ::id();
                $feed = UserFeed ::create($input);

                $message = ' Applied to a Competition on Enter.';
                if (isset(Auth ::user() -> friendsOfMineNetwork) && count(Auth ::user() -> friendsOfMineNetwork)) {
                    $network -> sendNetworkNotification(Auth ::user() -> friendsOfMineNetwork, $message);
                }
                if (isset(Auth ::user() -> friendOfNetwork) && count(Auth ::user() -> friendOfNetwork)) {
                    $network -> sendNetworkNotification(Auth ::user() -> friendOfNetwork, $message);
                }

                return redirect() -> back() -> with('success', 'Enter - Request Sent Successfully');
            } else {
                return redirect() -> back() -> with('error', 'You have not Permission to apply');
            }
        } else {
            return redirect() -> back() -> with('error', 'Error, Please Contact Us');
        }
    }

    public function sendDiscoverRequestMail($userdata)
    {
        // Send mail to User
        $setting = settings();
        Mail ::to($setting[0] -> email) -> send(new DiscoverRequestMail($userdata));
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
