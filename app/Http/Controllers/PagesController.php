<?php namespace App\Http\Controllers;

use App\Http\Requests;

class PagesController extends Controller {

	public function about() {

		$people = [
			'Paul Neal',
			'Cybelle LaPorte',
		];

		return view('pages.about', compact('people'));
	}

	public function contact() {

		$first = 'Paul';
		$last = 'Neal';

		return view('pages.contact', compact('first', 'last'));
	}

}
