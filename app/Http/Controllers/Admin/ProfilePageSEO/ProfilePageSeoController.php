<?php

namespace App\Http\Controllers\Admin\ProfilePageSEO;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProfilePageSEO\ProfilePageSeo;

class ProfilePageSeoController extends Controller
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
        $profilepageseo = ProfilePageSeo::find(1);
        if(!isset($profilepageseo) && empty($profilepageseo))
        {
            ProfilePageSeo::insertGetId([
                'id'    => 1
            ]);
            $profilepageseo = ProfilePageSeo::find(1);
            return view('admin.SEO.profilepageseo',compact('profilepageseo'));
        }
        else
        {
            return view('admin.SEO.profilepageseo',compact('profilepageseo'));
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
        ProfilePageSeo::where('id','1')->update($input);
        return redirect()->route('profilepage.edit',1)->with('success', 'Updated Successfully.');
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
