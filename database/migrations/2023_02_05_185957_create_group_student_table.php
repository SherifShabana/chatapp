<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupStudentTable extends Migration {

	public function up()
	{
		Schema::create('group_student', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('group_id');
			$table->integer('student_id');
		});
	}

	public function down()
	{
		Schema::drop('group_student');
	}
}