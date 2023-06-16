<?php

namespace App\Http\Controllers;

// use App\Models\Department;
// use App\Models\YearLevel;
// use App\Models\Section;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Imports\UserImport;
use Excel;
use Maatwebsite\Excel\Fakes\ExcelFake;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $records = User::when($request->keyword, function ($query) use ($request) {
            $query->where('name', 'like', "%{$request->keyword}%");
        })->paginate(30);
        return view('Users.index', compact('records'));                     //  take a look
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new User();
        return view('Users.create', compact('model'));
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
                'email' => 'required|unique:users|email',
                'password' => 'required|min:6'
            ];

        $this->validate($request, $rules);
        $request->merge([
            'password' => bcrypt($request->username),
            'role' => 1
        ]);
        //$record = new Users;
        //$record ->name = $request->input('name');
        //$record->save();

        $record = User::create($request->all());
        flash()->success("success");                                                      //  take a look
        return redirect(route(name: 'User.index'));
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
        $model = User::findOrFail($id);
        return view('Users.edit', compact('model'));

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
            'email' => 'required|unique:users,email,'.$id.'|email',
            'password' => 'nullable|min:6'
        ];

        $this->validate($request, $rules);
        $record = User::findOrFail($id);
        $inputs = $request->except('password');
        if($request->password){
            $inputs['password'] = bcrypt($request->password);
        }
        $record->update($inputs);
        flash()->success("edited");
        return redirect(route(name: 'User.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $record = User::findOrFail($id);
        $record->delete();
        flash()->success('deleted');
        return back();

    }

    // public function import(Request $request)
    // {
    //     Excel::import(new ProfessorImport, request()->file('users_file'));
    //     return back();
    // }
}
