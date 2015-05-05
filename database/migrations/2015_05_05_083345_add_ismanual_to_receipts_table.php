<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsmanualToReceiptsTable extends Migration {

	public function up()
	{
		Schema::table('receipts', function(Blueprint $table)
		{
			$table->string('ismanual')->after('state');
			$table->string('referenceInvoiceNumber')->after('ismanual');
		});
	}

	public function down()
	{
		Schema::create('receipts', function(Blueprint $table)
		{
			$table->dropColumn('ismanual');
			$table->dropColumn('referenceInvoiceNumber');
		});
	}

}
