<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = Department::paginate(10);
        return view('departments.index',compact('records'));                     //  take a look
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new Department;
        return view('departments.create',compact('model'));                                          //  take a look
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $rules =[
        'name' => 'required'
       ];
       $massages =[
        'name.required' =>'name is required'
       ];
       $this ->validate($request,$rules,$massages);
       //$record = new department;
       //$record ->name = $request->input('name');
       //$record->save();

       $records = Department::create($request->all());
       flash()->success("success");                                                      //  take a look
       return redirect(route(name:'department.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  Department $department
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Department $department
     * @return \Illuminate\Http\Response
     */
    public function edit(Department $department)
    {
        return view('departments.edit',compact('department'));                  //  take a look

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Department $department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Department $department)
    {
        $department->update($request->all());
        flash()->success("edited");
        return redirect(route(name:'department.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Department $department
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department)
    {
        $department->delete();
        flash()->success('deleted');
        return back();

    }
}
