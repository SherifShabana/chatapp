<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use App\Models\Section;
use App\Models\Student;
use App\Models\YearLevel;
use App\Models\Department;
use Database\Factories\YearLevelFactory;
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
        //*Admins
        /* 
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
        ]); */



        //*Students
        /*  
        Student::create([
            'name' => 'Eman Mohamed',
            'username' => 'eman',
            'password' => bcrypt('eman'),
            'section_id' => 10,
        ]);
        Student::create([
            'name' => 'Sherif Shabana',
            'username' => 'sherif',
            'password' => bcrypt('sherif'),
            'section_id' => 34,
        ]);
        Student::create([
            'name' => 'Abdelrahman Ebrahim',
            'username' => 'abdelrahman',
            'password' => bcrypt('abdelrahman'),
            'section_id' => 34, 
        ]);
        Student::create([
            'name' => 'Rana Elsayed',
            'username' => 'ranaa',
            'password' => bcrypt('rana'),
            'section_id' => 34,
        ]);
        Student::create([
            'name' => 'Rana Alaa',
            'username' => 'rana',
            'password' => bcrypt('rana'),
            'section_id' => 34,
        ]); */


        //*Sections
        /* 
        Section::factory(12)->create([
            'name' => 'Section 1',
        ]); 
        Section::factory(12)->create([
            'name' => 'Section 2',
        ]); 
        Section::factory(12)->create([
            'name' => 'Section 3',
        ]); 
       Section::factory(12)->create([
            'name' => 'Section 4',
        ]); 
        Section::factory(12)->create([
            'name' => 'Section 5',
        ]); 
        Section::factory(12)->create([
            'name' => 'Section 6',
        ]); 
        Section::factory(12)->create([
            'name' => 'Section 7',
        ]); */
        Section::factory(12)->create([
            'name' => 'Section 8',
        ]); 
        



        //*Year Level
        /* 
        YearLevel::factory(3)->create([
            'name' => 'Year Level 1',
        ]);
        YearLevel::factory(3)->create([
            'name' => 'Year Level 2',
        ]);
        YearLevel::factory(3)->create([
            'name' => 'Year Level 3',
        ]);
        YearLevel::factory(3)->create([
            'name' => 'Year Level 4',
        ]); */

        //*Department
        
        Department::create([
            'name' => 'Computer Science',
        ]);
        Department::create([
            'name' => 'Information Systems',
        ]);
        Department::create([
            'name' => 'Accounting',
        ]);
    }
}
