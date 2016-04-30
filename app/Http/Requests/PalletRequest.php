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
		if($this->exists('btn_Cancel')) return [];
		return [
			// Here you can refer to the fields in pallet/create.blade.php & pallet/edit.blade.php
			// See: laravel.com/docs/validation
			'Pallet_ID' => 'min:4',
			'x' => 'numeric',
			'y' => 'numeric',
			'z' => 'numeric',
			'Status' => 'required|In:LOCK,PUTAWAY,OPEN,LOADED,SHIPPED',
		];
	}

}
