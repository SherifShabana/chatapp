<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\Section;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            //dd($row);
            $section = Section::where('name', trim( $row['section']))
                ->whereHas('yearLevel', function($query) use($row){
                    $query->where('name', trim($row['year_level']));
                    $query->whereHas('department',function($query) use($row){
                        $query->where('name', trim($row['department']));
                    });
            })->first();

            if($section){
                Student::updateOrCreate([
                    'username' => $row["username"]
                ],[
                    'name' => $row["name"],
                    'username' => $row["username"],
                    'password' => bcrypt($row['username']),

                    // 'password'=> $row (bcrypt('username'));
                    'section_id' => $section->id,
                ]);
            }


        }
    }
}
