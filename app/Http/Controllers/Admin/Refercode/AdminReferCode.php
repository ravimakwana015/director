<?php

namespace App\Http\Controllers\Admin\Refercode;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Refercode\Refercode;
use Illuminate\Support\Facades\Mail;
use App\Mail\sendReferRequestMail;

class AdminReferCode extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.refercode.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.refercode.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'admin_refer_code' => 'required',
        ],[
            'name.required' => 'Please Enter Name',
            'email.required' => 'Please Enter Email',
            'admin_refer_code.required' => 'Please Enter Refer Code'
        ]);

        $input = $request->all();


        Refercode::create([
            'friend_name'  => $input['name'],
            'friend_email' => $input['email'],
            'refer_code' => $input['admin_refer_code']
        ]);

        $this->sendReferRequestMail($input);

        return redirect('admin/refercode')->with('success','Code Added Successfully');
    }

    public function sendReferRequestMail($userdata)
    {
        Mail::to($userdata['email'])->send(new sendReferRequestMail($userdata));
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
        //
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
        //
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
