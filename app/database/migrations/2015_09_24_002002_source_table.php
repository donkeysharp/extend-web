<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SourceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sources', function($t) {
			$t->increments('id');
			$t->string('source');
			$t->string('description', 500)->nullable();
			$t->timestamps();
		});
		print_r(DB::getQueryLog());
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('sources');
	}

}
