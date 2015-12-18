<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class ReceiptHistoryRequest extends Request {

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
			// Here you can refer to the fields in pages/receiptHistory/create.blade.php & pages/receiptHistory/edit.blade.php
			// See: laravel.com/docs/validation
			'PO' => 'required|numeric|min:8',
			'POD' => 'required|numeric|min:8',
			'Article' => 'required|numeric|min:8',
			'UPC' => 'required|numeric|min:8',
			'Inventory' => 'required|numeric|min:8',
			'Tote' => 'required|numeric|min:8',
			'Cart' => 'required|numeric|min:8',
			'Location' => 'required|numeric|min:8',
			'User_Name' => 'required|min:4',
			'Activity' => 'required|min:8',
		];
	}

}
