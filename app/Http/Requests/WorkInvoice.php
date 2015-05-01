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
			// 'organization.name'					=> 'required',
			// 'organization.address'				=> 'required',
			// 'organization.phoneNo'				=> 'required',
			// 'organization.city'					=> 'required',
			// 'organization.state'				=> 'required',
			// 'organization.country'				=> 'required',
			// 'organization.email'				=> 'required|email',
			// 'organization.rules'				=> 'required',
			// 'organization.note'					=> 'required',
			// 'organization.bankDetails'			=> 'required',
			'customer.name'						=> 'required',
			// 'customer.address'					=> 'required',
			// 'customer.email'					=> 'required|email',
			// 'customer.city'						=> 'required',
			// 'customer.state'					=> 'required',
			// 'customer.country'					=> 'required',

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
		$messages['organization.invoiceNumber.required'] = 'The field  invoice number is required.';
		$messages['organization.serviceDate.required'] = 'The field  service date is required.';
		$messages['organization.currency.required'] = 'The field  currency is required.';
		// $messages['organization.name.required'] = 'The field  company name is required.';
		// $messages['organization.address.required'] = 'The field company address is required.';
		// $messages['organization.phoneNo.required'] = 'The field company phone no is required.';
		// $messages['organization.city.required'] = 'The field  company city is required.';
		// $messages['organization.state.required'] = 'The field  company state is required.';
		// $messages['organization.country.required'] = 'The field  company country is required.';
		// $messages['organization.email.required'] = 'The field  company email is required.';
		// $messages['organization.email.email'] = 'The field  email address must be a valid email';
		// $messages['organization.note.required'] = 'The field note is required.';
		// $messages['organization.rules.required'] = 'The field note is required.';
		// $messages['organization.bankDetails.required'] = 'The field note is required.';
		$messages['customer.name.required'] = 'The field  customer name is required.';
		$messages['customer.address.required'] = 'The field  customer address is required.';
		// $messages['customer.email.required'] = 'The field customer email is required.';
		// $messages['customer.email.email'] = 'The field  email address must be a valid email';
		// $messages['customer.city.required'] = 'The field  customer city is required.';
		// $messages['customer.state.required'] = 'The field  customer state is required.';
		// $messages['customer.country.required'] = 'The field  customer country is required.';

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
