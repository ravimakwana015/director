<?php

namespace App\Http\Controllers\Admin\Explore;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\Explore\Explore;

class ExploreController extends Controller
{

    protected $upload_path;
    protected $storage;

    public function __construct()
    {
        $this -> upload_path = 'img' . DIRECTORY_SEPARATOR . 'explore' . DIRECTORY_SEPARATOR;
        $this -> storage = Storage ::disk('public');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.explore.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.explore.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request -> validate([
            'title' => 'required|regex:/(^[A-Za-z0-9 -]+$)+/',
//            'country' => 'required',
            'location' => 'required',
            'description' => 'required',
            'icon' => 'required|image',
            'workshop_image' => 'required|image',
            'link' => 'required|url',
            'job_type' => 'required',
        ], [
            'title.required' => 'Please Enter Title',
            'location.required' => 'Please Enter Location',
            'job_type.required' => 'Please Select Job Type',
            'description.required' => 'Please Enter Description',
            'icon.required' => 'Please Select Icon',
            'title.regex' => "Only alphanumeric characters are allowed"
        ]);

        $input = $request -> all();

        // Uploading icon
        if (isset($input['icon_img']) && !empty($input['icon_img'])) {
            $input['icon'] = $input['icon_img'];
        } elseif (array_key_exists('icon', $input) && !empty($input['icon'])) {
            $input = $this -> uploadImage($input);
        }

        if (array_key_exists('workshop_image', $input) && !empty($input['workshop_image'])) {

            $workshop_image = $input['workshop_image'];

            $fileName = time() . $workshop_image -> getClientOriginalName();

            $this -> storage -> put($this -> upload_path . $fileName, file_get_contents($workshop_image -> getRealPath()));

            $input['workshop_image'] = $fileName;
        }
        $input['slug']=strtolower(str_replace(' ', '-', $input['title']));
        Explore ::create($input);

        return redirect('admin/explore') -> with('success', 'Develop Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $explore = Explore ::find($id);
        return view('admin.explore.edit', compact('explore'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $validatedData = $request -> validate([
            'title' => 'required|regex:/(^[A-Za-z0-9 -]+$)+/',
//            'country' => 'required',
            'location' => 'required',
            'description' => 'required',
            'job_type' => 'required',
            'workshop_image' => 'sometimes|image',
            'link' => 'required|url',
        ], [
            'title.required' => 'Please Enter Title',
            'location.required' => 'Please Enter Location',
            'job_type.required' => 'Please Select Job Type',
            'description.required' => 'Please Enter Description',
            'title.regex' => "Only alphanumeric characters are allowed"
        ]);

        $input = $request -> all();
        $forumcategory = Explore ::find($id);
        if (isset($input['icon_img']) && !empty($input['icon_img'])) {
            $input['icon'] = $input['icon_img'];
        } elseif (array_key_exists('icon', $input) && !empty($input['icon'])) {
            $input = $this -> uploadImage($input);
        }
        if (array_key_exists('workshop_image', $input) && !empty($input['workshop_image'])) {

            $workshop_image = $input['workshop_image'];

            $fileName = time() . $workshop_image -> getClientOriginalName();

            $this -> storage -> put($this -> upload_path . $fileName, file_get_contents($workshop_image -> getRealPath()));

            $input['workshop_image'] = $fileName;
        }
        $input['slug']=strtolower(str_replace(' ', '-', $input['title']));
        $forumcategory -> update($input);
        return redirect('admin/explore') -> with('success', 'Develop Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $discovers = Explore ::findOrFail($id);
        $discovers -> delete();
        return redirect('admin/explore') -> with('error', 'Develop - Deleted Successfully');
    }

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

    public function uploadExploreIcon(Request $request)
    {
        $input = $request -> all();


        list($type, $input['image']) = explode(';', $input['image']);
        list(, $input['image']) = explode(',', $input['image']);


        $data = base64_decode($input['image']);
        $image_name = time() . '.png';
        $path = public_path() . "/img/explore/" . $image_name;
        file_put_contents($path, $data);
        if (isset($input['id'])) {
            $discovers = Explore ::find($input['id']);
            $discovers -> update(['icon' => $image_name]);
        }
        echo $image_name;
    }
}
