<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Models\Career\Career;
use App\Models\CareerRequest\CareerRequest;
use App\Mail\CareerRequestMail;
use App\Notifications\LikeNotification;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;


class CareerController extends Controller
{

    protected $upload_path;
    protected $storage;

    /**
     * CareerController constructor.
     */
    public function __construct()
    {
        $this -> upload_path = 'documents' . DIRECTORY_SEPARATOR . 'career_cv' . DIRECTORY_SEPARATOR;

        $this -> storage = Storage ::disk('public');
    }

    /**
     * Show the Career.
     * @param Request $request
     * @return array|View|string
     * @throws Throwable
     */
    public function index(Request $request)
    {
        $input = $request -> all();
        $query = Career ::query();
        if ($request -> ajax()) {
            if (isset($input['search']) && $input['search'] != '') {

                $search = $input['search'];
                $query -> where(function ($fq) use ($search) {
                    $fq -> orWhere('title', 'LIKE', '%' . explode(' ', $search)[0] . '%');
                    $fq -> orWhere('location', 'LIKE', '%' . $search . '%');
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
            $careers = $query -> orderby('created_at', 'DESC') -> where('status', 1) -> paginate(25);
            if (count($careers) == 0) {
                return view('career.ajaxblanckcareer', compact('input')) -> render();
            } else {
                return view('career.ajax-career', compact('careers')) -> render();
            }
        } else {
            $careers = $query -> orderby('created_at', 'DESC') -> where('status', 1) -> paginate(25);
            return view('career.career', compact('careers'));
        }
    }

    /**
     * @return JsonResponse
     */
    public function getCareerSearchOptions()
    {
        $country = Career ::groupBy('country') -> whereNotNull('country') -> pluck('country');
        return response() -> json([
            'country' => $country,
        ]);
    }

    /**
     * Show the Career Application Form.
     * @param $slug
     * @return Factory|View
     */
    public function applicationForm($slug)
    {
        $career = Career ::Where('slug', $slug) -> first();
        if (isset($career)) {
            return view('career.career-form', compact('career'));
        } else {
            return redirect() -> back() -> with('error', 'Career is not Available');
        }
    }

    /**
     * Send Career Application Form.
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function sendApplication(Request $request)
    {
        $this -> validate($request, [
            // 'profile_name'=>'required',
            // 'email'=>'required|email|unique:career_requests,email',
            'cover_letter' => 'required',
            'cv' => 'required|mimes:pdf,docx'
        ]);
        $input = $request -> except('_token');
        $setting = settings();
        if (isset($setting[0] -> email) && $setting[0] -> email) {
            if (Auth ::user() -> status == 1) {
                // Uploading Cv
                if (array_key_exists('cv', $input) && !empty($input['cv'])) {
                    $input = $this -> uploadImage($input);
                }
                $ip = request() -> ip();
                $input['user_id'] = Auth ::user() -> id;
                $input['profile_name'] = Auth ::user() -> first_name . ' ' . Auth ::user() -> last_name;
                $input['email'] = Auth ::user() -> email;
                $career = CareerRequest ::create($input);
                $this -> sendCareerRequestMail($input);
                likeProfile(Auth ::user() -> id, Auth ::user() -> id, $ip, 'career');

                $msg = ucfirst(Auth ::user() -> first_name) . ' ' . ucfirst(Auth ::user() -> last_name) . ' You Have Received 1 star to your Profile';
                Auth ::user() -> notify(new LikeNotification($msg));

                activity()
                    -> causedBy(Auth ::user())
                    -> performedOn($career)
                    -> withProperties(['career' => $career -> career -> slug])
                    -> log('Apply on Career');

                return redirect() -> back() -> with('success', 'Career Opportunity - Sent Successfully');
            } else {
                return redirect() -> back() -> with('error', 'You have not Permission to apply');
            }
        } else {
            return redirect() -> back() -> with('error', 'Error, Please Contact Us');
        }

    }

    /**
     * @param $userdata
     */
    public function sendCareerRequestMail($userdata)
    {
        // Send mail to User
        $setting = settings();
        Mail ::to($setting[0] -> email) -> send(new CareerRequestMail($userdata));
    }

    /**
     * Upload Image.
     *
     * @param array $input
     *
     * @return array $input
     */
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
