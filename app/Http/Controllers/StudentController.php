<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\YearLevel;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = Student::paginate(20);
        return view('student.index',compact('records'));                     //  take a look
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new Student();

        $departments = Department::pluck('name','id')->toArray();
       // should be removed later

       $Section = [];
        return view('student.create',compact('model','departments','yearLevels','section'));
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
        'name' => 'required',
        'section_id' => 'required'
       ];

       $this ->validate($request,$rules);
       //$record = new student;
       //$record ->name = $request->input('name');
       //$record->save();

       $record = Student::create($request->all());
       flash()->success("success");                                                      //  take a look
       return redirect(route(name:'student.index'));
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
        $model = Student::findOrFail($id);
        $departments = Department::pluck('name','id')->toArray();
       // should be removed later

       $Section = Student::where('department_id',$model->section->yearLevel->department_id)->pluck('name','id')->toArray();
        return view('student.edit',compact('model','departments','yearLevels','section'));

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
        $record = student::findOrFail($id);
        $record->update($request->all());
        flash()->success("edited");
        return redirect(route(name:'student.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $record=student::findOrFail($id);
        $record->delete();
        flash()->success('deleted');
        return back();

    }
}
