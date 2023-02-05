<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // \App\Models\User::factory(10)->create();

         \App\Models\User::factory()->create([
             'name' => 'Test User',
             'email' => 'test@example.com',
             'password' => bcrypt('123123'),
             'role' => 1,
         ]);

         \App\Models\Student::create([
             'name' => 'Student 1',
             'username' => 'std1',
             'password' => bcrypt('123123'),
             'section_id' => 1,
         ]);
         \App\Models\Student::create([
             'name' => 'Student 1',
             'username' => 'std2',
             'password' => bcrypt('123123'),
             'section_id' => 1,
         ]);
         \App\Models\Student::create([
             'name' => 'Student 3',
             'username' => 'std3',
             'password' => bcrypt('123123'),
             'section_id' => 1,
         ]);

         \App\Models\Section::create([
             'name' => 'Section 1',
             'year_level_id' => 1,
         ]);

         \App\Models\YearLevel::create([
             'name' => 'YL 1',
             'department_id' => 1,
         ]);

         \App\Models\Department::create([
             'name' => 'CS',
         ]);
    }
}
