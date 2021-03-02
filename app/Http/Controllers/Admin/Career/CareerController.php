<?php

namespace App\Http\Controllers\Admin\Career;


use App\Http\Requests\Admin\Career\ManageCareerRequest;
use App\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Career\Career;
use App\Repositories\Admin\Career\CareerRepository;
use App\Http\Requests\Admin\Career\StoreCareerRequest;
use App\Http\Requests\Admin\Career\UpdateCareerRequest;
use App\Http\Responses\Admin\Career\CreateResponse;
use DB;
use Hash;
use Illuminate\Http\Response;


class CareerController extends Controller
{
    /**
     * @var CareerRepository
     */
    protected $career;

    /**
     * @param CareerRepository $career
     */
    public function __construct(CareerRepository $career)
    {
        $this -> career = $career;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('admin.career.index');
    }


    /**
     * @return CreateResponse
     */
    public function create()
    {
        return new CreateResponse();
    }


    /**
     * Store a newly created resource in storage.
     * @param StoreCareerRequest $request
     * @return RedirectResponse
     */
    public function store(StoreCareerRequest $request)
    {
        $this -> career -> create($request -> except('_token'));
        return redirect() -> route('careers.index')
            -> with('success', 'Career Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return void
     */
    public function show($id)
    {

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $careers = Career ::find($id);

        return view('admin.career.edit', compact('careers'));
    }


    /**
     * @param UpdateCareerRequest $request
     *
     * @return RedirectResponse
     */
    public function update(UpdateCareerRequest $request, $id)
    {
        $input = $request -> except('_token');

        $career = Career ::find($id);
        if ($input['country'] == '') {
            $input['country'] = $career -> country;
        } else {
            $input['country'] = $input['country'];
        }

        $this -> career -> update($career, $input, $id);

        return redirect() -> route('careers.index') -> with('success', 'Career Updated Successfully');
    }

    /**
     * Delete Career
     */
    public function destroy($id)
    {
        $career = Career ::find($id);
        $this -> career -> delete($career);

        return redirect() -> route('careers.index')
            -> with('success', 'Career Deleted Successfully');
    }

    public function uploadCareerIcon(Request $request)
    {
        $input = $request -> all();


        list($type, $input['image']) = explode(';', $input['image']);
        list(, $input['image']) = explode(',', $input['image']);


        $data = base64_decode($input['image']);
        $image_name = time() . '.png';
        $path = public_path() . "/img/career_icon/" . $image_name;
        file_put_contents($path, $data);
        if (isset($input['id'])) {
            $career = Career ::find($input['id']);
            if ($career -> icon != '' && file_exists(public_path('img/career_icon/') . $career -> icon)) {
                unlink(public_path('img/career_icon/') . $career -> icon);
            }
            $career -> update(['icon' => $image_name]);
        }
        echo $image_name;
    }

    public function uploadAdminIcon(Request $request)
    {
        $input = $request -> all();


        list($type, $input['image']) = explode(';', $input['image']);
        list(, $input['image']) = explode(',', $input['image']);


        $data = base64_decode($input['image']);
        $image_name = time() . '.png';
        $path = public_path() . "/img/profile_picture/" . $image_name;
        file_put_contents($path, $data);
        if (isset($input['id'])) {
            $career = User ::find($input['id']);
            if ($career -> icon != '' && file_exists(public_path('img/profile_picture/') . $career -> icon)) {
                unlink(public_path('img/profile_picture/') . $career -> icon);
            }
            $career -> update(['profile_picture' => $image_name]);
        }
        echo $image_name;
    }
}
