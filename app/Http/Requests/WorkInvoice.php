<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Validation\Validator;
use Illuminate\Http\JsonResponse;

class WorkInvoice extends Request {

	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		$rules = [
			'organization.invoiceNumber' 		=> 'required',
			'organization.serviceDate' 			=> 'required',			
			'organization.currency' 			=> 'required',
			'organization.name'					=> 'required',
			'organization.address'				=> 'required',
			'organization.phoneNo'				=> 'required',
			'organization.city'					=> 'required',
			'organization.state'				=> 'required',
			'organization.country'				=> 'required',
			'organization.email'				=> 'required|email',
			'organization.rules'				=> 'required',
			'organization.note'					=> 'required',
			'organization.bankDetails'			=> 'required',
			'customer.name'						=> 'required',
			'customer.address'					=> 'required',
			'customer.email'					=> 'required|email',
			'customer.city'						=> 'required',
			'customer.state'					=> 'required',
			'customer.country'					=> 'required',

		];

		// foreach($this->request->get('allDesc') as $key => $val)
		// {
		// 	$rules['workDescription.'.$key] = 'required';
		// }
		return $rules;
	}

	// protected function formatErrors(Validator $validator)
	// {
	//     return $validator->errors()->all();
	// }

	// public function messages()
	// {
	// 	$messages = [];
	// 	foreach($this->request->get('workDescription') as $key => $val)
	// 	{
	// 		$messages['workDescription.'.$key.'.required'] = 'The field description '.$key.' is required.';
	// 	}

	// 	foreach($this->request->get('rate') as $key => $val)
	// 	{			
	// 		$messages['rate.'.$key.'.required'] = 'The field rate '.$key.' is required.';
	// 		$messages['rate.'.$key.'.numeric'] = 'The field rate '.$key.' must be numeric value.';
	// 	}

	// 	foreach($this->request->get('hour') as $key => $val)
	// 	{
	// 		$messages['hour.'.$key.'.required'] = 'The field hour '.$key.' is required.';
	// 		$messages['hour.'.$key.'.numeric'] = 'The field hour '.$key.' must be numeric value.';
	// 	}

	//   return $messages;
	// }

	public function response(array $errors)
	{
		if (Request::ajax())
		{
			return new JsonResponse($errors);
		}

		return $this->redirector->to($this->getRedirectUrl())
                                        ->withInput($this->except($this->dontFlash))
                                        ->withErrors($errors, $this->errorBag);
	}

}
