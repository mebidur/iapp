<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class ServiceInvoice extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
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
			'bankDetails'			=> 'required',
			'termsCondition'		=> 'required',
			'keyNote' 				=> 'required'
		];
	}

}
