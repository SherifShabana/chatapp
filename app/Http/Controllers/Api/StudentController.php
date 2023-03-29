<?php

namespace App\Http\Controllers\Api;

use App\Models\Channel;
use App\Models\Student;
use App\Models\Group;
use App\Models\Department;
use App\Models\YearLevel;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use PHPUnit\TextUI\XmlConfiguration\Groups;
use App\Http\Controllers\Controller;

class studentController extends Controller
{
    public function index(){
        return view('student');
      }

      public function getUsers(){

        $student = Student::orderby('id','asc')->select('*')->get();

        // Fetch all records
        $response['data'] = $student;

        return response()->json($response);
      }


      public function chatList(Request $request){

        $student = $request->user();// [student] || admin
        //dd($student);


        $channels = Channel::whereHasMorph(
            'chattable',
            [Student::class, Group::class, Department::class, YearLevel::class, Section::class],
            function (Builder $query, string $type) use($student) {
                switch($type){
                    case Student::class :
                        $query->where('students.id',$student->id);
                        break;
                    case Groups::class :
                        $query->whereHas('students',function($query) use($student){
                            $query->where('student_id',$student->id);
                        });
                        break;

                    case Section::class :
                        $query->whereHas('students',function($query) use($student){
                            $query->where('students.id',$student->id);
                        });
                        break;

                    case YearLevel::class :
                        $query->whereHas('sections',function($query) use($student){
                                $query->whereHas('students',function($query) use($student){
                                    $query->where('students.id',$student->id);
                                });
                            });
                        break;
                    case Department::class :
                        $query->whereHas('yearLevels',function($query) use($student){
                                $query->whereHas('sections',function($query) use($student){
                                     $query->whereHas('students',function($query) use($student){
                                            $query->where('students.id',$student->id);
                                        });
                                    });
                                });
                        break;
                }
            }
      )->get();

      return response()->json($channels);
     }

        /* $userid = $request->userid;

         $students = Students::select('*')->where('id', $userid)->get();

         // Fetch all records
         $response['data'] = $students;
        */


         public function channelMessages(Request $request){

            $channel = Channel::find($request->channel_id);
            $messages =$channel->messages()->get();

            return response()->json($messages);
      }
     /* public function channelMessages(Request $request){
       $channel = Channel::find($request->channel_id);
        if(!$channel){
            return response()->json([
                'error' => "Channel not found"
            ],400);
        }
            $messages =$channel->messages()->paginate();

            return response()->json($messages);
            // return [
            //     'channel' => $channel,
            //     'messages' => $messages
            //     ];
              } */


}
