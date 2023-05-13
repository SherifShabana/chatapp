<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\YearLevel;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Imports\StudentImport;
use Excel;
use Maatwebsite\Excel\Fakes\ExcelFake;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $records = Student::when($request->keyword, function ($query) use ($request) {
            $query->where('name', 'like', "%{$request->keyword}%");
        })->paginate(20);
        return view('student.index', compact('records'));                     //  take a look
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new Student();

        $departments = Department::pluck('name', 'id')->toArray();
        // should be removed later
        $yearLevels = [];
        $sections = [];
        return view('student.create', compact('model', 'departments', 'yearLevels', 'sections'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'username' => 'required|unique:students',
            'section_id' => 'required'
        ];

        $this->validate($request, $rules);
        $request->merge(['password' => bcrypt($request->username)]);
        //$record = new student;
        //$record ->name = $request->input('name');
        //$record->save();

        $record = Student::create($request->all());
        flash()->success("success");                                                      //  take a look
        return redirect(route(name: 'student.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = Student::findOrFail($id);
        $departments = Department::pluck('name', 'id')->toArray();
        $yearLevels = YearLevel::where('department_id', $model->section->yearLevel->department_id)
            ->pluck('name', 'id')->toArray();

        $sections = Section::where('year_level_id', $model->section->year_level_id)->pluck('name', 'id')->toArray();
        return view('student.edit', compact('model', 'departments', 'yearLevels', 'sections'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required',
            'username' => 'required|unique:students,username,' . $id,
            'section_id' => 'required'
        ];

        $this->validate($request, $rules);
        $record = student::findOrFail($id);
        $record->update($request->all());
        flash()->success("edited");
        return redirect(route(name: 'student.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $record = student::findOrFail($id);
        $record->delete();
        flash()->success('deleted');
        return back();

    }

    public function import(Request $request)
    {
        Excel::import(new StudentImport, request()->file('students_file'));
        return back();
    }
}
