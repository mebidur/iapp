<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReceiptsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('receipts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('receiptNumber');
			$table->integer('organization_id');
			$table->integer('customer_id');			
			$table->timestamp('serviceDate');
			$table->string('currency');
			$table->string('type');
			$table->integer('state');
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
		Schema::drop('receipts');
	}

}
