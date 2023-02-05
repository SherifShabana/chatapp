<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateParticipantsTable extends Migration {

	public function up()
	{
		Schema::create('participants', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('channel_id');
			$table->integer('user_id');
		});
	}

	public function down()
	{
		Schema::drop('participants');
	}
}
