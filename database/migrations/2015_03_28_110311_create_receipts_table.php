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
			$table->string('keyNote');
			$table->string('currency');
			$table->string('type');
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
