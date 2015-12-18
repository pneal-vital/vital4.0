<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class inventoryRequest extends Request {

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
			// Here you can refer to the fields in pages/inventory/create.blade.php & pages/inventory/edit.blade.php
			// See: laravel.com/docs/validation
			'Item' => 'required|numeric',
			'Quantity' => 'required|numeric',
			'Order_Line' => 'required|numeric',
			'UOM' => 'required|numeric',
		];
	}

}
