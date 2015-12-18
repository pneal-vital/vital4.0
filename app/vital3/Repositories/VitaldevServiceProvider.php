<?php namespace App\vital3\Repositories;

use Illuminate\Support\ServiceProvider;

class VitaldevServiceProvider extends ServiceProvider {

	/**
	 * Register a Service Provider
	 */
	public function register() {

		/*
		 * bind an interface name with a concrete implementor
		 */
		$this->app->bind(
			'vital3\Repositories\ClientRepositoryInterface',
			'vital3\Repositories\DBClientRepository'
		);
		$this->app->bind(
			'vital3\Repositories\CountersRepositoryInterface',
			'vital3\Repositories\DBCountersRepository'
		);
        $this->app->bind(
            'vital3\Repositories\EventQueueRepositoryInterface',
            'vital3\Repositories\DBEventQueueRepository'
        );
        $this->app->bind(
            'vital3\Repositories\EventsRepositoryInterface',
            'vital3\Repositories\DBEventsRepository'
        );
		$this->app->bind(
			'vital3\Repositories\InboundOrderRepositoryInterface',
			'vital3\Repositories\DBInboundOrderRepository'
		);
		$this->app->bind(
			'vital3\Repositories\InboundOrderDetailRepositoryInterface',
			'vital3\Repositories\DBInboundOrderDetailRepository'
		);
        $this->app->bind(
            'vital3\Repositories\InventoryRepositoryInterface',
            'vital3\Repositories\DBInventoryRepository'
        );
        $this->app->bind(
            'vital3\Repositories\LocationRepositoryInterface',
            'vital3\Repositories\DBLocationRepository'
        );
        $this->app->bind(
            'vital3\Repositories\PalletRepositoryInterface',
            'vital3\Repositories\DBPalletRepository'
        );
        $this->app->bind(
            'vital3\Repositories\UOMRepositoryInterface',
            'vital3\Repositories\DBUOMRepository'
        );
        $this->app->bind(
            'vital3\Repositories\VitalObjectRepositoryInterface',
            'vital3\Repositories\DBVitalObjectRepository'
        );

	}
}

