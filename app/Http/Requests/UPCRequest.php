<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class UPCRequest extends Request {

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
			// Here you can refer to the fields in upc/create.blade.php & upc/edit.blade.php
			// See: laravel.com/docs/validation
			'Sku_Number' => 'required|min:8',
			'Client_SKU' => 'required|min:8',
			'Description' => 'required|min:10',
			'UOM' => 'required|min:2',
		    'Retail_Price' => 'required|numeric',
			'UPC' => 'required|min:10',
			'Zone' => 'required|min:10',
			'Description_2' => 'required|min:10',
		];
	}

}
