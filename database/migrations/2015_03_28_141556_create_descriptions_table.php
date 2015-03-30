<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDescriptionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('descriptions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('invoice_id');
			$table->integer('receipt_id');
			$table->string('workDescription');
			$table->string('rate');
			$table->string('hour');
			$table->string('amount');
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('descriptions');
	}

}
