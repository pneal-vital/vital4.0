<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class UserActivityRequest extends Request {

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
			// Here you can refer to the fields in pages/userActivity/create.blade.php & pages/userActivity/edit.blade.php
			// See: laravel.com/docs/validation
			'id' => 'required|numeric|min:8',
			'classID' => 'required|min:4',
			'User_Name' => 'required|min:4',
			'Purpose' => 'required|min:4',
		];
	}

}
