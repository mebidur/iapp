<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class Config extends Request {

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
			'name' 			=> 'required',
			'address' 		=> 'required',
			'phoneNo' 		=> 'required',
			'email' 		=> 'required|email',
			'city' 			=> 'required',
			// 'state' 		=> 'required',
			'country' 		=> 'required',
			'rules' 		=> 'required',
			'bankDetails' 	=> 'required',
			'note' 			=> 'required'
		];
	}

}
