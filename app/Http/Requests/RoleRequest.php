<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class RoleRequest extends Request {

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
			// Here you can refer to the fields in pages/role/create.blade.php & pages/role/edit.blade.php
			// See: laravel.com/docs/validation
			'name' => 'required|min:4',
			'display_name' => 'required|min:4',
			'description' => 'required|min:6',
		];
	}

}
