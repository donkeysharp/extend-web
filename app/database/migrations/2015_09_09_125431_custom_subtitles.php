<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CustomSubtitles extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('custom_subtitles', function($t) {
			$t->increments('id');
			$t->integer('client_id')->unsigned();
			$t->integer('subtitle_id')->unsigned();
			$t->timestamps();

			$t->foreign('subtitle_id')->references('id')->on('subtitles');
			$t->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
		});
		// print_r(DB::getQueryLog());
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('custom_subtitles');
	}

}
