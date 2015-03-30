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
			$table->string('serviceProvider');
			$table->timestamp('serviceDate');
			$table->string('serviceReceiver');
			$table->string('companyAddress');
			$table->string('clientAddress');
			$table->integer('description_id');
			$table->string('termsCondition');
			$table->string('bankDetails');
			$table->string('keyNote');
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
