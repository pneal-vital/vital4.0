<?php namespace App\Reports;

use Illuminate\Support\ServiceProvider;

class ReportServiceProvider extends ServiceProvider {

	/**
	 * Register Reports
	 */
	public function register() {

		/*
		 * bind an interface name with a concrete implementor
		 */
		$this->app->bind(
			'App\Reports\ReworkReportInterface',
			'App\Reports\ReworkReport'
		);

	}
}

