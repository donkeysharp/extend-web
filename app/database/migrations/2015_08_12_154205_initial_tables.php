<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InitialTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('topics', function($t) {
			$t->increments('id');
			$t->string('name');
			$t->string('description')->nullable();
			$t->timestamps();
		});

		Schema::create('media', function($t) {
			$t->increments('id');
			$t->integer('type'); // Written, Digital, TV, Radio, Source
			$t->string('name');
			$t->string('description')->nullable();
			$t->string('city');
			$t->string('website')->nullable();
			$t->timestamps();
		});

		Schema::create('clients', function($t) {
			$t->increments('id');
			$t->string('name');
			$t->string('phone');
			$t->string('description')->nullable();
			$t->string('address')->nullable();
			$t->string('city');
			$t->string('website');
			$t->timestamps();
		});

		Schema::create('contacts', function($t) {
			$t->increments('id');
			$t->string('name');
			$t->string('rank')->nullable();
			$t->string('position')->nullable();
			$t->string('email')->nullable();
			$t->string('phone')->nullable();
			$t->integer('client_id')->unsigned();
			$t->timestamps();

			$t->foreign('client_id')->references('id')->on('clients')
				->onDelete('cascade');
		});

		Schema::create('news', function($t){
			$t->increments('id');
			$t->string('press_note');
			$t->string('code')->nullable();
			$t->string('clasification');
			$t->date('date');
			$t->integer('client_id')->unsigned()->nullable();
			$t->timestamps();

			$t->foreign('client_id')->references('id')->on('clients')
				->onDelete('set null');
		});

		Schema::create('news_urls', function($t) {
			$t->increments('id');
			$t->string('url');
			$t->integer('news_id')->unsigned();
			$t->timestamps();

			$t->foreign('news_id')->references('id')->on('news')
				->onDelete('cascade');
		});

		Schema::create('news_uploads', function($t) {
			$t->increments('id');
			$t->string('type');
			$t->string('file_name');
			$t->integer('news_id')->unsigned()->nullable();
			$t->timestamps();

			$t->foreign('news_id')->references('id')->on('news')
				->onDelete('set null');
		});

		Schema::create('news_details', function($t) {
			$t->increments('id');
			$t->integer('type');
			$t->string('title');
			$t->text('description')->nullable();
			$t->string('tendency');
			$t->string('section')->nullable();
			$t->string('page')->nullable();
			$t->string('gender')->nullable();
			$t->string('web')->nullable();
			$t->string('source')->nullable();
			$t->string('sourceTendency')->nullable();
			$t->string('alias')->nullable();
			$t->integer('measure')->nullable();
			$t->integer('cost')->nullable();
			$t->string('communication_risk')->nullable();
			$t->string('show')->nullable();
			$t->integer('news_id')->unsigned();
			$t->integer('topic_id')->unsigned()->nullable();
			$t->integer('media_id')->unsigned()->nullable();
			$t->timestamps();

			$t->foreign('news_id')->references('id')->on('news')
				->onDelete('cascade');
			$t->foreign('topic_id')->references('id')->on('topics')
				->onDelete('set null');
			$t->foreign('media_id')->references('id')->on('media')
				->onDelete('set null');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('news_details');
		Schema::dropIfExists('news_uploads');
		Schema::dropIfExists('news_urls');
		Schema::dropIfExists('news');
		Schema::dropIfExists('contacts');
		Schema::dropIfExists('clients');
		Schema::dropIfExists('media');
		Schema::dropIfExists('topics');
	}

}
