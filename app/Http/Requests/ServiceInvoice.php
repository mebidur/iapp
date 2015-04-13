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
		$rules = [
			// 'invoiceNumber' 		=> 'required|max:20',
			// 'serviceDate' 			=> 'required',
			// 'serviceProvider' 		=> 'required',
			// 'serviceReceiver' 		=> 'required',
			// 'companyAddress' 		=> 'required',
			// 'clientAddress' 		=> 'required',
			// 'currency' 				=> 'required',
			// 'bankDetails'			=> 'required',
			// 'termsCondition'		=> 'required',
			// 'keyNote' 				=> 'required'
		];

		// foreach ($this->request->get('workDescription') as $key => $value) {
		// 	$rules['workDescription.'.$key] = 'required';
		// }
		// foreach ($this->request->get('amount') as $key => $value) {
		// 	$rules['amount.'.$key] = 'required|numeric';
		// }

		return $rules;
	}

	// public function messages()
	// {
	// 	$messages = [];
	// 	foreach ($this->request->get('workDescription') as $key => $value) {
	// 		$messages['workDescription.'.$key.'.required'] =' The field work description '.$key.' is required.';
	// 	}
	// 	foreach ($this->request->get('amount') as $key => $value) {
	// 		$messages['amount.'.$key.'.required'] = 'The field amount '.$key.' is required';
	// 		$messages['amount.'.$key.'.numeric'] = 'The field amount '.$key.' should be numeric value';
	// 	}
	// 	return $messages;
	// }

}
