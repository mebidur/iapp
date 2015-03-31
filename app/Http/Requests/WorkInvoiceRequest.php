<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class WorkInvoiceRequest extends Request {

	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		return [
			'invoiceNumber' 		=> 'required',
			'serviceDate' 			=> 'required',
			'serviceProvider' 		=> 'required',
			'serviceReceiver' 		=> 'required',
			'companyAddress' 		=> 'required',
			'clientAddress' 		=> 'required',
			'currency' 				=> 'required',
			'workDescription[]' 	=> 'required',
			'rate[]' 				=> 'required',
			'hour[]' 				=> 'required',
			'termsCondition' 		=> 'required',
			'bankDetails' 			=> 'required',
			'keyNote' 				=> 'required'
		];
	}

}
