<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddServiceDateToReceiptsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('receipts', function(Blueprint $table)
		{
			$table->date('serviceDate')->after('receiptNumber');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::create('receipts', function(Blueprint $table)
		{
			$table->dropColumn('serviceDate');
		});
	}

}
