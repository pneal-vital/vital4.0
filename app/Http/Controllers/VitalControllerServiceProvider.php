<?php namespace App\Http\Controllers;

use Illuminate\Support\ServiceProvider;

class VitalControllerServiceProvider extends ServiceProvider {

	/**
	 * Register Controllers
	 */
	public function register() {

		/*
		 * bind an interface name with a concrete implementor
		 */
		$this->app->bind(
			'App\Http\Controllers\ClientControllerInterface',
			'App\Http\Controllers\ClientController'
		);
		$this->app->bind(
			'App\Http\Controllers\JobStatusControllerInterface',
			'App\Http\Controllers\JobStatusController'
		);
		$this->app->bind(
			'App\Http\Controllers\LocationControllerInterface',
			'App\Http\Controllers\LocationController'
		);
		$this->app->bind(
			'App\Http\Controllers\PalletControllerInterface',
			'App\Http\Controllers\PalletController'
		);
		$this->app->bind(
			'App\Http\Controllers\PermissionControllerInterface',
			'App\Http\Controllers\PermissionController'
		);
		$this->app->bind(
			'App\Http\Controllers\Receive\ReceivePOControllerInterface',
			'App\Http\Controllers\Receive\ReceivePOController'
		);
		$this->app->bind(
			'App\Http\Controllers\RoleControllerInterface',
			'App\Http\Controllers\RoleController'
		);
		$this->app->bind(
			'App\Http\Controllers\ToteControllerInterface',
			'App\Http\Controllers\ToteController'
		);
		$this->app->bind(
			'App\Http\Controllers\UOMControllerInterface',
			'App\Http\Controllers\UOMController'
		);

	}
}

