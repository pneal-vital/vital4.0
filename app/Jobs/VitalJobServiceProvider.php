<?php namespace App\Jobs;

use Illuminate\Support\ServiceProvider;

class VitalJobServiceProvider extends ServiceProvider {

	/**
	 * Register Job Services
	 */
	public function register() {

		/*
		 * bind an interface name with a concrete implementor
		 */
		$this->app->bind(
			'App\Jobs\JobExperienceInterface',
			'App\Jobs\JobExperience'
		);
		$this->app->bind(
			'App\Jobs\JobStatusInterface',
			'App\Jobs\JobStatus'
		);

	}
}

