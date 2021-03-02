<?php

namespace App\Http\Controllers\Admin\AccessCode;

use App\Models\AccessCode\AccessCode;
use Exception as ExceptionAlias;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;

class AccessCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        return view('admin.accessCode.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        return view('admin.accessCode.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Redirector
     */
    public function store(Request $request)
    {
        $validatedData = $request -> validate([
            'code' => 'required|unique:access_codes',
            'status' => 'required',
        ], [
            'code.required' => 'Please Enter Access Code',
            'status.required' => 'Please Select Status',
        ]);

        $input = $request -> all();
        AccessCode ::create($input);

        return redirect('admin/access-code') -> with('success', 'Access Code Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param AccessCode $accessCode
     * @return Response
     */
    public function show(AccessCode $accessCode)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param AccessCode $accessCode
     * @return View
     */
    public function edit(AccessCode $accessCode)
    {
        return view('admin.accessCode.edit', compact('accessCode'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param AccessCode $accessCode
     * @return RedirectResponse|Redirector
     */
    public function update(Request $request, AccessCode $accessCode)
    {
        $validatedData = $request -> validate([
            'code' => 'required|unique:access_codes,code,' . $accessCode -> id,
            'status' => 'required',
        ], [
            'code.required' => 'Please Enter Access Code',
            'status.required' => 'Please Select Status',
        ]);
        $update = $request -> all();
        $accessCode -> update($update);
        return redirect('admin/access-code') -> with('success', 'Access Code Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param AccessCode $accessCode
     * @return RedirectResponse|Redirector
     * @throws ExceptionAlias
     */
    public function destroy(AccessCode $accessCode)
    {
        $accessCode->delete();
        return redirect('admin/access-code') -> with('success', 'Access Code Deleted Successfully');
    }
}
