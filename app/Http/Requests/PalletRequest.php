<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class PalletRequest extends Request {

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
			// Here you can refer to the fields in pallet/create.blade.php & pallet/edit.blade.php
			// See: laravel.com/docs/validation
			'Pallet_ID' => 'min:8',
			'x' => 'required|numeric',
			'y' => 'required|numeric',
			'z' => 'required|numeric',
			'Status' => 'required|In:LOCK,OPEN,LOADED,SHIPPED',
		];
	}

}
