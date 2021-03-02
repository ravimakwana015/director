<?php

namespace App\Http\Controllers\Admin\ExploreListSEO;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ExploreListSEO\ExploreListSeo;

class ExploreListSeoController extends Controller
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
        $explorelistseo = ExploreListSeo::find(1);
        if(!isset($explorelistseo) && empty($explorelistseo))
        {
            ExploreListSeo::insertGetId([
                'id'    => 1
            ]);
            $explorelistseo = ExploreListSeo::find(1);
            return view('admin.SEO.explorelistseo',compact('explorelistseo'));
        }
        else
        {
            return view('admin.SEO.explorelistseo',compact('explorelistseo'));
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
        ExploreListSeo::where('id','1')->update($input);
        return redirect()->route('explorelist.edit',1)->with('success', 'Updated Successfully.');
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
