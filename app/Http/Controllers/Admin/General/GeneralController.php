<?php

namespace App\Http\Controllers\Admin\General;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\generalseo\GeneralSeo;

class GeneralController extends Controller
{


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
        $generalseo = GeneralSeo::find(1);
        if(!isset($generalseo) && empty($generalseo))
        {
            GeneralSeo::insertGetId([
                'id'    => 1
            ]);
            $generalseo = GeneralSeo::find(1);
            return view('admin.SEO.generalseo',compact('generalseo'));
        }
        else
        {
            return view('admin.SEO.generalseo',compact('generalseo'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GeneralSeo $GeneralSeo)
    {
        $input=$request->except('_token','_method');
        GeneralSeo::where('id','1')->update($input);
        return redirect()->route('general.edit',1)->with('success', 'Updated Successfully.');
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
