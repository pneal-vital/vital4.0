<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class LocationRequest extends Request {

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
			// Here you can refer to the fields in location/create.blade.php & location/edit.blade.php
			// See: laravel.com/docs/validation
			'Location_Name' => 'min:2',
			'Capacity' => 'required|numeric',
			'x' => 'required|numeric',
			'y' => 'required|numeric',
			'z' => 'required|numeric',
			'LocType' => 'required|min:4',
			'Comingle' => 'required|in:N,P',
		];
	}

}
