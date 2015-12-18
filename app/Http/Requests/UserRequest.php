<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class UserRequest extends Request {

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
			// Here you can refer to the fields in pages/user/create.blade.php & pages/user/edit.blade.php
			// See: laravel.com/docs/validation
			'name' => 'required|min:4',
			'email' => 'required|min:4',
		];
	}

}
