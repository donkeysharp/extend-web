<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BulletinTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bulletins', function($t) {
			$t->increments('id');
			$t->string('name')->nullable();
			$t->integer('client_id')->unsigned()->nullable();
			$t->timestamps();

			$t->foreign('client_id')->references('id')->on('clients')
				->onDelete('set null');
		});
		Schema::create('bulletin_news_detail', function($t) {
			$t->increments('id');
			$t->integer('news_detail_id')->unsigned();
			$t->integer('bulletin_id')->unsigned();
			$t->timestamps();

			$t->foreign('news_detail_id')->references('id')->on('news_details')
				->onDelete('cascade');
			$t->foreign('bulletin_id')->references('id')->on('bulletins')
				->onDelete('cascade');
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
		Schema::dropIfExists('bulletin_news_detail');
		Schema::dropIfExists('bulletins');
	}

}
