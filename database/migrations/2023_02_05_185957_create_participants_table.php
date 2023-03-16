<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
