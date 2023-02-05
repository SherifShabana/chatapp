<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMessagesTable extends Migration {

	public function up()
	{
		Schema::create('messages', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('channel_id');
			$table->text('content');
			$table->integer('user_id');
		});
	}

	public function down()
	{
		Schema::drop('messages');
	}
}
