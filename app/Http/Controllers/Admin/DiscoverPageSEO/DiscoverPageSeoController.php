<?php

namespace App\Http\Controllers\Admin\DiscoverPageSEO;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DiscoverPageSEO\DiscoverPageSeo;

class DiscoverPageSeoController extends Controller
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
        $discoverpageseo = DiscoverPageSeo::find(1);
        if(!isset($discoverpageseo) && empty($discoverpageseo))
        {
            DiscoverPageSeo::insertGetId([
                'id'    => 1
            ]);
            $discoverpageseo = DiscoverPageSeo::find(1);
            return view('admin.SEO.discoverpageseo',compact('discoverpageseo'));
        }
        else
        {
            return view('admin.SEO.discoverpageseo',compact('discoverpageseo'));
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
        DiscoverPageSeo::where('id','1')->update($input);
        return redirect()->route('discoverpage.edit',1)->with('success', 'Updated Successfully.');
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
