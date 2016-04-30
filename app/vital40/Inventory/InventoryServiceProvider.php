<?php namespace App\vital40\Inventory;

use Illuminate\Support\ServiceProvider;

class InventoryServiceProvider extends ServiceProvider {

	/**
	 * Register a Service Provider
	 */
	public function register() {

		/*
		 * bind an interface name with a concrete implementor
		 */
		$this->app->bind(
			'App\vital40\Inventory\ComingleRulesInterface',
			'App\vital40\Inventory\ComingleRules'
		);

	}
}

