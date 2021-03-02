<?php

namespace App\Http\Controllers\Admin\CareerSEO;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CareerSEO\CareerSeo;

class CareerSeoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $careerlistseo = CareerSeo::find(1);
        if(!isset($careerlistseo) && empty($careerlistseo))
        {
            CareerSeo::insertGetId([
                'id'    => 1
            ]);
            $careerlistseo = CareerSeo::find(1);
            return view('admin.SEO.careerlistseo',compact('careerlistseo'));
        }
        else
        {
            return view('admin.SEO.careerlistseo',compact('careerlistseo'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input=$request->except('_token','_method');
        CareerSeo::where('id','1')->update($input);
        return redirect()->route('careerlist.edit',1)->with('success', 'Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
