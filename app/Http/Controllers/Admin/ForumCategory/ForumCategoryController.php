<?php

namespace App\Http\Controllers\Admin\ForumCategory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ForumCategory\ForumCategory;

class ForumCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.forumcategory.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.forumcategory.create');
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
            'title' => 'required|unique:forum_categories',
        ],[
            'title.required' => 'Please Enter Forum Category',
            'title.unique' => 'Duplicate Forum Category Not Allowed'
        ]);

        $input = $request->all();

        ForumCategory::create([
            'title'  => $input['title'],
            'status' => $input['status']
        ]);

        return redirect('admin/forumcategory')->with('success','Forum Category Added Successfully');
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
        $forumcategory = ForumCategory::find($id);
        return view('admin.forumcategory.edit',compact('forumcategory'));
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
        $validatedData = $request->validate([
            'title' => 'required|unique:forum_categories,title,'.$id.'',
        ],[
            'title.required' => 'Please Enter Forum Category',
            'title.unique' => 'Duplicate Forum Category Not Allowed'
        ]);

        $update = $request->all();
        $forumcategory = ForumCategory::find($id);

        $forumcategory->update([
            'title'  => $update['title'],
            'status' => $update['status']
        ]);
        return redirect('admin/forumcategory')->with('success','Forum Category Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $forumcategorydelete = ForumCategory::findOrFail($id);
        $forumcategorydelete->delete();
        return redirect('admin/forumcategory')->with('error','Forum Category Delete Successfully !!');
    }
}
