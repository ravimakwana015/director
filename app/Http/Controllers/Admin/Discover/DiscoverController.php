<?php

namespace App\Http\Controllers\Admin\Discover;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Discovers\Discovers;
use App\Models\EnterResults\EnterResults;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Throwable;
use Validator;

class DiscoverController extends Controller
{
    protected $upload_path;
    protected $result_upload_path;
    protected $storage;

    public function __construct()
    {
        $this -> upload_path = 'img' . DIRECTORY_SEPARATOR . 'discover' . DIRECTORY_SEPARATOR;
        $this -> result_upload_path = 'img' . DIRECTORY_SEPARATOR . 'discover_results' . DIRECTORY_SEPARATOR;
        $this -> storage = Storage ::disk('public');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('admin.discover.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.discover.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $validatedData = $request -> validate([
            'competitions' => 'required',
//            'country' => 'required',
            'title' => 'required|regex:/(^[A-Za-z0-9 -]+$)+/',
            'description' => 'required',
            'icon' => 'required|image',
            'status' => 'required',
        ], [
            'competitions.required' => 'Please Select Competitions',
            'title.required' => 'Please Enter Title',
            'description.required' => 'Please Enter Description',
            'icon.required' => 'Please Select Icon',
            'title.regex' => "Only alphanumeric characters and - are allowed"
        ]);

        $input = $request -> all();
        if (isset($input['icon_img']) && !empty($input['icon_img'])) {
            $input['icon'] = $input['icon_img'];
        } elseif (array_key_exists('icon', $input) && !empty($input['icon'])) {
            $input = $this -> uploadImage($input);
        }

        Discovers ::create([
            'competitions' => $input['competitions'],
            'title' => $input['title'],
            'description' => $input['description'],
            'icon' => $input['icon'],
            'status' => $input['status'],
            'country' => ''
        ]);

        return redirect('admin/discover') -> with('success', 'Enter Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return void
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $discover = Discovers ::find($id);
        return view('admin.discover.edit', compact('discover'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request -> validate([
            'competitions' => 'required',
//            'country' => 'required',
            'title' => 'required|regex:/(^[A-Za-z0-9 -]+$)+/',
            'description' => 'required',
            // 'icon' => 'required|image',
             'status' => 'required',
        ], [
            'competitions.required' => 'Please Select Competitions',
            'title.required' => 'Please Enter Title',
            'description.required' => 'Please Enter Description',
            'title.regex' => "Only alphanumeric characters and - are allowed"
            // 'icon.required' => 'Please Select Icon',
        ]);

        $input = $request -> all();
        $forumcategory = Discovers ::find($id);
        if (array_key_exists('icon', $input)) {
            $input = $this -> uploadImage($input);
            $forumcategory -> update([
                'competitions' => $input['competitions'],
                'title' => $input['title'],
                'description' => $input['description'],
                'icon' => $input['icon'],
                'status' => $input['status'],
                'country' => ''
            ]);
        } else {
            $forumcategory -> update([
                'competitions' => $input['competitions'],
                'title' => $input['title'],
                'description' => $input['description'],
                'status' => $input['status'],
                'country' => ''
            ]);
        }
        return redirect('admin/discover') -> with('success', 'Enter Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $discover = Discovers ::findOrFail($id);
        $discover -> delete();
        return redirect('admin/discover') -> with('error', 'Enter - Deleted Successfully');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function declareResult(Request $request)
    {
        $input = $request -> all();

        $rules = array(
            'photo' => 'required',
            'enter_id' => 'required',
            'user_id' => 'required',
        );
        $message = array(
            'photo.required' => 'Please Upload Photo Or Video',
            'user_id.required' => 'Please Select Winner',
        );
        if (isset($input['photo'])) {
            $extension = $request -> file('photo') -> extension();
            if ($extension == 'png' || $extension == 'jpeg' || $extension == 'jpg') {
                $rules['photo'] = 'required|max:2000';
                $message['photo.max'] = 'The Image has to be less than or equal to 2mb.';
            } else if ($extension == 'mkv' || $extension == 'mp4') {
                $rules['photo'] = 'required|max:10000';
                $message['photo.max'] = 'The video has to be less than or equal to 10mb.';
            } else {
                return response() -> json(array(
                    'status' => false,
                    'msg' => ['Only image and Video allowed']
                ));
            }
        } else {
            return response() -> json(array(
                'status' => false,
                'msg' => ['Please Upload Photo Or Video']
            ));
        }

        $validator = Validator ::make($request -> all(), $rules, $message);
        if ($validator -> fails()) {
            return response() -> json(array(
                'status' => false,
                'msg' => $validator -> errors()
            ));
        } else {
            $icon = $input['photo'];
            $fileName = time() . $icon -> getClientOriginalName();
            $this -> storage -> put($this -> result_upload_path . $fileName, file_get_contents($icon -> getRealPath()));
            $input['data'] = $fileName;
            unset($input['photo']);
            EnterResults ::create($input);
            $discover = Discovers ::findOrFail($input['enter_id']);
            $discover -> update([
                'status' => 2
            ]);
            return response() -> json(array(
                'status' => true,
                'msg' => ['Result Declared Successfully']
            ));
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getCompetitionUser(Request $request)
    {
        $input = $request -> all();
        $discover = Discovers ::findOrFail($input['id']);
        if (isset($discover -> getCompetitionRequest) && count($discover -> getCompetitionRequest)) {
            $users = [];
            foreach ($discover -> getCompetitionRequest as $competitor) {
                $users[] = [
                    'user_id' => $competitor -> usersid -> id,
                    'name' => $competitor -> usersid -> first_name . ' ' . $competitor -> usersid -> last_name,
                    'username' => $competitor -> usersid -> username
                ];
            }

            return response() -> json([
                'users' => $users,
            ], 200);
        } else {
            return response() -> json([
                'errors' => 'No User apply for the competition',
            ], 400);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function viewWinnerUser(Request $request)
    {
        $input = $request -> all();
        $discover = Discovers ::findOrFail($input['id']);
        if (isset($discover)) {
           $data =  view('admin.discover.winner', compact('discover'))->render();
           return response() -> json([
                'data' => $data,
            ], 200);
        } else {
            return response() -> json([
                'errors' => 'Enter Not Available',
            ], 400);
        }
    }

    /**
     * @param $input
     * @return mixed
     */
    public function uploadImage($input)
    {
        if (isset($input['icon']) && !empty($input['icon'])) {
            $icon = $input['icon'];

            $fileName = time() . $icon -> getClientOriginalName();

            $this -> storage -> put($this -> upload_path . $fileName, file_get_contents($icon -> getRealPath()));

            $input['icon'] = $fileName;
            return $input;
        }
    }

    /**
     * @param Request $request
     */
    public function uploadDiscoverIcon(Request $request)
    {
        $input = $request -> all();


        list($type, $input['image']) = explode(';', $input['image']);
        list(, $input['image']) = explode(',', $input['image']);


        $data = base64_decode($input['image']);
        $image_name = time() . '.png';
        $path = public_path() . "/img/discover/" . $image_name;
        file_put_contents($path, $data);
        if (isset($input['id'])) {
            $discovers = Discovers ::find($input['id']);
            $discovers -> update(['icon' => $image_name]);
        }
        echo $image_name;
    }
}
