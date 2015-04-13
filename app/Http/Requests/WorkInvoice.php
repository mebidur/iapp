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
			// 'organization.invoiceNumber' 		=> 'required|numeric|max:20',
			// 'serviceDate' 			=> 'required',
			// 'serviceProvider' 		=> 'required',
			// 'serviceReceiver' 		=> 'required',
			// 'companyAddress' 		=> 'required',
			// 'clientAddress' 		=> 'required',
			// 'currency' 				=> 'required',
			// 'termsCondition' 		=> 'required',
			// 'bankDetails' 			=> 'required',
			// 'keyNote' 				=> 'required'
		];

		// foreach($this->request->get('workDescription') as $key => $val)
		// {
		// 	$rules['workDescription.'.$key] = 'required';
		// }

		// foreach($this->request->get('rate') as $key => $val)
		// {
		// 	$rules['rate.'.$key] = 'required|numeric';
		// }

		// foreach($this->request->get('hour') as $key => $val)
		// {
		// 	$rules['hour.'.$key] = 'required|numeric';
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
