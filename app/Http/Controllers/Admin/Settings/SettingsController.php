<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Models\Settings\Settings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;


class SettingsController extends Controller
{

    /**
     * Site Logo Path.
     *
     * @var string
     */
    protected $site_logo_path;
    /**
     * Storage Class Object.
     *
     * @var \Illuminate\Support\Facades\Storage
     */
    protected $storage;

    /**
     * Constructor.
     */
    public function __construct()
    {

    	$this->site_logo_path = 'img'.DIRECTORY_SEPARATOR.'logo'.DIRECTORY_SEPARATOR;
    	$this->storage = Storage::disk('public');
    }

    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Settings\Settings  $settings
     * @return \Illuminate\Http\Response
     */
    public function edit(Settings $settings)
    {
        //$profilepageseo = ProfilePageSeo::find(1);
        $settings = Settings::find(1);
        if(!isset($settings) && empty($settings))
        {
            Settings::insertGetId([
                'id'    => 1
            ]);
            $settings = Settings::find(1);
            return view('admin.settings.index',compact('settings'));
        }
        else
        {
            return view('admin.settings.index',compact('settings'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Settings\Settings  $settings
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {     
        $input=$request->except('_token','_method');

        if(!empty($input['logo'])){
            $logo= $input['logo'];
            $image_name = time().$logo->getClientOriginalName();
            $this->storage->put($this->site_logo_path.$image_name, file_get_contents($logo->getRealPath())); 
            $input['logo']=$image_name;
            Settings::where('id','1')->update($input);
        }else{
            Settings::where('id','1')->update($input);
        }

        if(!empty($input['default_image'])){
            $default_image= $input['default_image'];
            $image_name = time().$default_image->getClientOriginalName();
            $this->storage->put($this->site_logo_path.$image_name, file_get_contents($default_image->getRealPath())); 
            $input['default_image']=$image_name;
            Settings::where('id','1')->update($input);
        }else{
            Settings::where('id','1')->update($input);
        }

        return redirect()->route('settings.edit',1)->with('success', 'Settings Updated Successfully.');
    }

}
