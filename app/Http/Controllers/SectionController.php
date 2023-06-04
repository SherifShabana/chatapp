<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\YearLevel;
use App\Models\Section;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = Section::paginate(100);
        return view('Section.index',compact('records'));                     //  take a look
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new Section();

        $departments = Department::pluck('name','id')->toArray();
       // should be removed later

       $yearLevels = [];
        return view('Section.create',compact('model','departments','yearLevels'));
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
        'year_level_id' => 'required'
       ];

       $this ->validate($request,$rules);
       //$record = new YearLevel;
       //$record ->name = $request->input('name');
       //$record->save();

       $record = Section::create($request->all());
       flash()->success("success");                                                      //  take a look
       return redirect(route(name:'section.index'));
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
        $model = Section::findOrFail($id);
        $departments = Department::pluck('name','id')->toArray();
       // should be removed later

       $yearLevels = YearLevel::where('department_id',$model->yearLevel->department_id)->pluck('name','id')->toArray();
        return view('Section.edit',compact('model','departments','yearLevels'));

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
        $record = Section::findOrFail($id);
        $record->update($request->all());
        flash()->success("edited");
        return redirect(route(name:'section.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $record=Section::findOrFail($id);
        $record->delete();
        flash()->success('deleted');
        return back();

    }
}
