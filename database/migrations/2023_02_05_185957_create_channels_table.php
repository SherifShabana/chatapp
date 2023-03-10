<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChannelsTable extends Migration {

	public function up()
	{
		Schema::create('channels', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->morphs('chattable');
            $table->string('name');
		});
	}

	public function down()
	{
		Schema::drop('channels');
	}
}
