<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use App\Models\Section;
use App\Models\Student;
use App\Models\YearLevel;
use App\Models\Department;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //Admins
        User::factory()->create([
            'name' => 'Dr.Bahaa Shabana',
            'email' => 'tester@met.com',
            'password' => bcrypt('123123'),
            'role' => 1, //admin
        ]);

        User::factory()->create([
            'name' => 'Eng.Ahmed Eldemoksy',
            'email' => 'tester2@met.com',
            'password' => bcrypt('123123'),
            'role' => 1, //admin
        ]);


        //Students
        Student::create([
            'name' => 'Eman Mohamed',
            'username' => 'eman',
            'password' => bcrypt('eman'),
            'section_id' => 1,
        ]);
        Student::create([
            'name' => 'Sherif Shabana',
            'username' => 'sherif',
            'password' => bcrypt('sherif'),
            'section_id' => 3,
        ]);
        Student::create([
            'name' => 'Abdelrahman Ebrahim',
            'username' => 'abdelrahman',
            'password' => bcrypt('abdelrahman'),
            'section_id' => 3,
        ]);
        Student::create([
            'name' => 'Rana Elsayed',
            'username' => 'ranaa',
            'password' => bcrypt('rana'),
            'section_id' => 3,
        ]);
        Student::create([
            'name' => 'Rana Alaa',
            'username' => 'rana',
            'password' => bcrypt('rana'),
            'section_id' => 3,
        ]);


        //Sections
        Section::create([
            'name' => 'Section 1',
            'year_level_id' => 4,
        ]);
        Section::create([
            'id' => '3',
            'name' => 'Section 3',
            'year_level_id' => 4,
        ]);


        //Year Level
        YearLevel::create([
            'id' => '4',
            'name' => 'YL 4',
            'department_id' => 1,
        ]);


        //Department
        Department::create([
            'name' => 'CS',
        ]);
    }
}
