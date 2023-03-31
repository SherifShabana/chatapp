<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYearLevelsTable extends Migration {

	public function up()
	{
		Schema::create('year_levels', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name');
			$table->integer('department_id');
		});
	}

	public function down()
	{
		Schema::drop('year_levels');
	}
}