<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class ToteRequest extends Request {

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
			// Here you can refer to the fields in tote/create.blade.php & tote/edit.blade.php
			// See: laravel.com/docs/validation
			'Carton_ID' => 'required|min:8',
			'Status'    => 'required|In:OPEN,RECD,REPLEN,LOADED,PUTAWAY',
		];
	}

}
