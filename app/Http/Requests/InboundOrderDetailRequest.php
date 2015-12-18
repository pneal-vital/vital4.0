<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class InboundOrderDetailRequest extends Request {

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
			// Here you can refer to the fields in pages/inboundOrder/create.blade.php & pages/inboundOrder/edit.blade.php
			// See: laravel.com/docs/validation
			'SKU' => 'required|min:10',
			'Expected_Qty' => 'required|numeric',
			'Actual_Qty' => 'required|numeric',
			'UPC' => 'required|min:8',
			'UOM' => 'required|min:10',
		];
	}

}
