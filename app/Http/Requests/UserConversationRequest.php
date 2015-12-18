<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class UserConversationRequest extends Request {

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
			// Here you can refer to the fields in pages/userConversation/create.blade.php & pages/userConversation/edit.blade.php
			// See: laravel.com/docs/validation
			'POD' => 'required|numeric|min:4',
			'Article' => 'required|numeric|min:4',
			'User_Name' => 'required|min:4',
			'Sender_Name' => 'required|min:4',
			'Text' => 'required|min:1',
		];
	}

}
