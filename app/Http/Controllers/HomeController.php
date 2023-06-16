<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Student;
use App\Models\YearLevel;
use App\Models\User;
use App\Models\Section;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $stats['Users'] = User::count();
        $stats['students'] = Student::count();
        $stats['departments'] = Department::count();
        $stats['yearlevels'] = YearLevel::count();
        $stats['sections'] = Section::count();
        return view('home', compact('stats'));
    }
}
