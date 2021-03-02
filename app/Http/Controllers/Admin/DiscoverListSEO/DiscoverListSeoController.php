<?php

namespace App\Http\Controllers\Admin\DiscoverListSEO;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DiscoverListSEO\DiscoverListSeo;

class DiscoverListSeoController extends Controller
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
        $discoverlistseo = DiscoverListSeo::find(1);
        if(!isset($discoverlistseo) && empty($discoverlistseo))
        {
            DiscoverListSeo::insertGetId([
                'id'    => 1
            ]);
            $discoverlistseo = DiscoverListSeo::find(1);
            return view('admin.SEO.discoverlistseo',compact('discoverlistseo'));
        }
        else
        {
            return view('admin.SEO.discoverlistseo',compact('discoverlistseo'));
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
        DiscoverListSeo::where('id','1')->update($input);
        return redirect()->route('discoverlist.edit',1)->with('success', 'Updated Successfully.');
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
