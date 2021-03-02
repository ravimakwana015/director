<?php
namespace App\Http\Controllers\Admin\Sliders;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Sliders\Sliders;
use App\Repositories\Admin\Sliders\SlidersRepository;
use App\Http\Requests\Admin\Sliders\StoreSlidersRequest;
use App\Http\Requests\Admin\Sliders\UpdateSlidersRequest;
use App\Http\Responses\Admin\Sliders\CreateResponse;
use App\Models\Country\Country;
use DB;
use Hash;


class SlidersController extends Controller
{
    /**
     * @var SlidersRepository
     */
    protected $sliders;

    /**
     * @param \App\Repositories\Admin\Sliders\SlidersRepository $sliders
     */
    public function __construct(SlidersRepository $sliders)
    {
        $this->sliders = $sliders;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('admin.sliders.index');
    }


    /**
     * @param \App\Http\Requests\Admin\Sliders\ManageSlidersRequest $request
     *
     * @return \App\Http\Responses\Admin\Sliders\CreateResponse
     */
    public function create()
    {
        return new CreateResponse();
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * @param \App\Http\Requests\Admin\Sliders\StoreSlidersRequest $request
     *
     * @return \App\Http\Responses\RedirectResponse
     */
    public function store(StoreSlidersRequest $request)
    {    

        $validatedData = $request->validate([
            'title' => 'required',
            'image' => 'required|mimes:jpeg,png,jpg|max:2000|dimensions:min_width=2500,min_height=1665',
            // 'status' => 'required',
        ],[
            'title.required' => 'Please Enter Page Title',
            'image.required' => 'Please Choose Image',
            'image.max' => 'Sorry! Maximum allowed size for an image is 2MB',
            'status.required' => 'Please Select Status',
            'image.dimensions'    => 'Invalid image dimensions it should be minimum 2500*1669',
        ]);

        $this->sliders->create($request->except('_token'));
        return redirect()->route('sliders.index')
        ->with('success','Sliders Created Successfully');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sliders = Sliders::find($id);

        return view('admin.sliders.edit',compact('sliders'));
    }


     /**
     * @param \App\Http\Requests\Admin\Sliders\UpdateSlidersRequest $request
     *
     */
     public function update(UpdateSlidersRequest $request, $id)
     {
       $sliders = Sliders::find($id);
       $this->sliders->update($sliders,$request->except('_token'),$id);

       return redirect()->route('sliders.index')->with('success','Sliders Updated Successfully');
   }

    /**
     * Delete Sliders
     */
    public function destroy($id)
    {   
        $sliders = Sliders::find($id);
        $this->sliders->delete($sliders);

        return redirect()->route('sliders.index')
        ->with('success','Sliders Deleted Successfully');
    }
}