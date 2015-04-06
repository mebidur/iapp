<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class WorkInvoice extends Request {

	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		$rules = [
			'invoiceNumber' 		=> 'required|max:20',
			'serviceDate' 			=> 'required',
			'serviceProvider' 		=> 'required',
			'serviceReceiver' 		=> 'required',
			'companyAddress' 		=> 'required',
			'clientAddress' 		=> 'required',
			'currency' 				=> 'required',
			'termsCondition' 		=> 'required',
			'bankDetails' 			=> 'required',
			'keyNote' 				=> 'required'
		];

		foreach($this->request->get('workDescription') as $key => $val)
		{
			$rules['workDescription.'.$key] = 'required';
		}

		foreach($this->request->get('rate') as $key => $val)
		{
			$rules['rate.'.$key] = 'required';
		}

		foreach($this->request->get('hour') as $key => $val)
		{
			$rules['hour.'.$key] = 'required';
		}

		return $rules;
	}

	public function messages()
	{
		$messages = [];
		foreach($this->request->get('workDescription') as $key => $val)
		{
			$messages['workDescription.'.$key.'.required'] = 'The field labeled "Description of Work '.$key.' is required.';
		}

		foreach($this->request->get('rate') as $key => $val)
		{			
			$messages['rate.'.$key.'.required'] = 'The field labeled "Rate '.$key.' is required.';
		}

		foreach($this->request->get('hour') as $key => $val)
		{
			$messages['hour.'.$key.'.required'] = 'The field labeled "Hour '.$key.' is required.';
		}

	  return $messages;
	}

}
