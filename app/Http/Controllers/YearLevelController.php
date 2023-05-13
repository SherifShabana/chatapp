<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\YearLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class YearLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = YearLevel::paginate(20);
        return view('year_levels.index',compact('records'));                     //  take a look
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new YearLevel();
        $departments = Department::pluck('name','id')->toArray();
        return view('year_levels.create',compact('model','departments'));                                      //  take a look
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
        'department_id' => 'required'
       ];
       $this ->validate($request,$rules);
       //$record = new YearLevel;
       //$record ->name = $request->input('name');
       //$record->save();

       $record = YearLevel::create($request->all());
       flash()->success("success");                                                      //  take a look
       return redirect(route(name:'year-level.index'));
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
        $model = YearLevel::findOrFail($id);
        $departments = Department::pluck('name','id')->toArray();
        return view('year_levels.edit',compact('model','departments'));                  //  take a look

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
        $record = YearLevel::findOrFail($id);
        $record->update($request->all());
        flash()->success("edited");
        return redirect(route(name:'year-level.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $record=YearLevel::findOrFail($id);
        $record->delete();
        flash()->success('deleted');
        return back();

    }
}
