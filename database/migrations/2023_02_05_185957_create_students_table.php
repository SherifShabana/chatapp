<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration {

	public function up()
	{
		Schema::create('students', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name');
            $table->string('username')->unique();
            $table->string('password');
			$table->integer('section_id');
		});
	}

	public function down()
	{
		Schema::drop('students');
	}
}
