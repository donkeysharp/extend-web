<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SubtitleColumnNewsDetails extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('news_details', function($t){
		    $t->string('subtitle')->nullable();
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
		Schema::table('news_details', function($t){
		    $t->dropColumn('subtitle');
		});
	}

}
