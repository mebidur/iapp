<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Http\JsonResponse;

class ServiceReceipt extends Request {

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
			'organization.receiptNumber' 		=> 'required',
			'organization.serviceDate' 			=> 'required',			
			'organization.currency' 			=> 'required',			
			'customer.name'						=> 'required',
			'customer.address'					=> 'required',
		];

		foreach($this->request->get('descs') as $index => $each)
		{
			$i = $index;
			foreach ($each as $key => $value) {
				if($key == 'amount'){
					$rules['descs.'.$i.'.'.$key] = 'required|numeric';
				}else{
					$rules['descs.'.$i.'.'.$key] = 'required';
				}
			}
		}

		return $rules;
	}

	public function messages()
	{
		$messages = [];
		$messages['organization.receiptNumber.required'] = 'The field  receipt number is required.';
		$messages['organization.serviceDate.required'] = 'The field  service date is required.';
		$messages['organization.currency.required'] = 'The field  currency is required.';
		$messages['customer.name.required'] = 'The field  customer name is required.';
		$messages['customer.address.required'] = 'The field  customer address is required.';

		foreach($this->request->get('descs') as $key => $val)
		{
			$i = $key;
			foreach ($val as $each => $value) {
				$field = ($each == 'workDescription') ? 'work description' : $each;
				$messages['descs.'.$i.'.'.$each.'.required'] = 'The field '.$field. ' ' .$i.' is required.';
				$messages['descs.'.$i.'.'.$each.'.numeric'] = 'The field '.$field. ' ' .$i.' must be a numeric value.';
				$messages['descs.'.$i.'.'.$each.'.email'] = 'The field '.$field. ' ' .$i.' must be a valid email.';
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
