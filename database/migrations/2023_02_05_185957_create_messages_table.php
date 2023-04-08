<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration {

	public function up()
	{
		Schema::create('messages', function(Blueprint $table) {
			$table->increments('id')->unsigned();
			$table->timestamps();
			$table->integer('channel_id');
			$table->text('content');
			$table->integer('user_id');
			$table->boolean('seen')->default(0);
		});
	}

	public function down()
	{
		Schema::drop('messages');
	}
}
