<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUnitToDescriptionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('descriptions', function(Blueprint $table)
		{
			$table->string('unit')->after('hour');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::create('descriptions', function(Blueprint $table)
		{
			$table->dropColumn('unit');
		});
	}

}
