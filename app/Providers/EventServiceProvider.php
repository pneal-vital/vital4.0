<?php namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\vital3\GenericContainer;
use App\vital3\InboundOrder;
use App\vital3\InboundOrderDetail;
use App\vital3\Inventory;
use App\vital3\Item;
use App\vital3\Location;
use App\vital3\Pallet;
//use App\vital40\JobExperience;
//use App\vital40\JobStatus;
use App\vital40\PerformanceTally;
use vital40\User;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\SomeEvent' => [
            'App\Listeners\EventListener',
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        GenericContainer::creating(function($genericContainer)
        {
            return $genericContainer->isCreating();
        });

        InboundOrder::creating(function($inboundOrder)
        {
            return $inboundOrder->isCreating();
        });

        InboundOrderDetail::creating(function($inboundOrderDetail)
        {
            return $inboundOrderDetail->isCreating();
        });

        Inventory::creating(function($inventory)
        {
            return $inventory->isCreating();
        });

        Item::creating(function($item)
        {
            return $item->isCreating();
        });
/*
        JobExperience::creating(function($jobExperience)
        {
            return $jobExperience->isCreating();
        });

        JobStatus::creating(function($jobStatus)
        {
            return $jobStatus->isCreating();
        });
*/
        Location::creating(function($location)
        {
            return $location->isCreating();
        });

        Pallet::creating(function($pallet)
        {
            return $pallet->isCreating();
        });

        PerformanceTally::creating(function($performanceTally)
        {
            return $performanceTally->isCreating();
        });

        User::creating(function($user)
        {
            return $user->isCreating();
        });

    }
}
