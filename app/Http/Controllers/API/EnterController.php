<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Models\Discovers\Discovers;
use App\Models\DiscoversRequests\DiscoversRequests;
use App\Mail\DiscoverRequestMail;
use Auth;
use App\Http\Controllers\API\ResponseController as ResponseController;
use Validator;


class EnterController extends ResponseController
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
        if (isset($input['search']) && $input['search'] != '') {

            $search = $input['search'];
            $enter = Discovers ::orWhere('title', 'like', '%' . $search . '%') -> orWhere('description', 'like', '%' . $search . '%') -> orderby('created_at', 'DESC') -> paginate(25);
            if (count($enter) == 0) {
                return $this -> sendSuccess('Enter not available for your search');
            }
        } else {
            $enter = Discovers ::where('status', 1) -> orderby('created_at', 'DESC') -> paginate(25);
        }
        return $this -> sendResponse($enter);
    }

    public function applicationForm(Request $request)
    {
        $validator = Validator ::make($request -> all(), [
            'title' => 'required',
        ]);
        if ($validator -> fails()) {
            return $this -> sendError($validator -> errors());
        }
        $title = $request -> all()['title'];
        $title = str_replace('-', ' ', $title);
        $discover = Discovers ::Where('title', $title) -> first();
        if (isset($discover)) {
            if ($discover -> status == 2) {
                    $data= [
                        'winner'=>$discover->getResult->usersid,
                        'winner_data'=>$discover->getResult,
                        'discover'=>$discover
                    ];
                    return $this -> sendResponse($data);
            } else {
                return $this -> sendResponse($discover);
            }
        } else {
            return $this -> sendSuccess('Competition is not Available');
        }
    }

    public function sendApplication(Request $request)
    {
        $validator = Validator ::make($request -> all(), [
            'cover_letter' => 'required',
            'enter_id' => 'required',
            'cv' => 'required|mimes:pdf,docx'
        ]);
        if ($validator -> fails()) {
            return $this -> sendError($validator -> errors());
        }
        $input = $request -> all();
        $setting = settings();
        $user = $request -> user();
        if (isset($setting[0] -> email) && $setting[0] -> email) {
            // Uploading Cv
            if (array_key_exists('cv', $input) && !empty($input['cv'])) {
                $input = $this -> uploadImage($input);
            }
            $ip = request() -> ip();
            $input['user_id'] = $user -> id;
            $input['profile_name'] = $user -> first_name . ' ' . $user -> last_name;
            $input['email'] = $user -> email;
            $input['discover_id'] = $input['enter_id'];
            DiscoversRequests ::create($input);
            $this -> sendDiscoverRequestMail($input);

            return $this -> sendSuccess('Enter - Request Sent Successfully');
        } else {
            return $this -> sendError('Error, Please Contact Us');
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
