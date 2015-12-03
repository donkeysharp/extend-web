<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReportTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('report_cache', function($t) {
			$t->string('md5')->primary();
			$t->text('data');
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
		Schema::dropIfExists('report_cache');
		print_r(DB::getQueryLog());
	}

}
