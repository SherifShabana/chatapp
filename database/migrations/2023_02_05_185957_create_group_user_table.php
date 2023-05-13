<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupUserTable extends Migration {

	public function up()
	{
		Schema::create('group_user', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('group_id');
			$table->integer('user_id');
		});
	}

	public function down()
	{
		Schema::drop('group_student');
	}
}