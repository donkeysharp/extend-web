<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExtraTitleTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('news_details', function($t) {
			$t->string('extra_title')->nullable();
			$t->string('observations', 1000)->nullable();
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
		Schema::table('news_details', function($t) {
			$t->dropColumn('extra_title')->nullable();
			$t->dropColumn('observations')->nullable();
		});
	}

}
