<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('invoices', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('invoiceNumber');
			$table->integer('organization_id');
			$table->string('serviceProvider');
			$table->timestamp('serviceDate');
			$table->string('serviceReceiver');
			$table->string('companyAddress');
			$table->string('clientAddress');
			$table->string('termsCondition');
			$table->string('bankDetails');
			$table->string('keyNote');
			$table->string('status');
			$table->string('currency');
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
		Schema::drop('invoices');
	}

}
