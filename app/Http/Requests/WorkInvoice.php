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

		foreach($this->request->get('allDesc') as $index => $each)
		{
			$i = $index;
			foreach ($each as $key => $value) {
				if($key == 'hour' || $key == 'rate'){
					$rules['allDesc.'.$i.'.'.$key] = 'required|numeric';
				}else{
					$rules['allDesc.'.$i.'.'.$key] = 'required';
				}
			}
		}
		return $rules;
	}

	public function messages()
	{
		$messages = [];
		foreach($this->request->get('allDesc') as $key => $val)
		{
			$i = $key;
			foreach ($val as $each => $value){
				$field = ($each == 'workDescription') ? 'work description' : $each;
				$messages['allDesc.'.$i.'.'.$each.'.required'] = 'The field '.$field. ' ' .$i.' is required.';
				$messages['allDesc.'.$i.'.'.$each.'.numeric'] = 'The field '.$field. ' ' .$i.' must be a numeric value.';
				$messages['allDesc.'.$i.'.'.$each.'.email'] = 'The field '.$field. ' ' .$i.' must be a valid email.';
			}
		}

	  return $messages;
	}

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
