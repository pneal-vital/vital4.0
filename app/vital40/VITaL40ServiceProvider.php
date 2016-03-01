<?php namespace App\vital40;

use Illuminate\Support\ServiceProvider;

class VITaL40ServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register VITaL4.0 application services.
	 */
	public function register()
	{
        $this->bindInterfaceImplementor();

        $this->registerArticleFlow();

		$this->registerLocationFlow();

        $this->registerPOReconciliation();

		$this->registerPurchaseOrderFlow();
	}

    /**
     * Bind DB accessor interface name with a concrete implementor.
     */
    protected function bindInterfaceImplementor()
    {
        $this->app->bind(
            'vital40\Repositories\ArticleRepositoryInterface',
            'vital40\Repositories\DBArticleRepository'
        );
        $this->app->bind(
            'vital40\Repositories\InventorySummaryRepositoryInterface',
            'vital40\Repositories\DBInventorySummaryRepository'
        );
        $this->app->bind(
            'vital40\Repositories\PerformanceTallyRepositoryInterface',
            'vital40\Repositories\DBPerformanceTallyRepository'
        );
        $this->app->bind(
            'vital40\Repositories\JobExperienceRepositoryInterface',
            'vital40\Repositories\DBJobExperienceRepository'
        );
        $this->app->bind(
            'vital40\Repositories\JobStatusRepositoryInterface',
            'vital40\Repositories\DBJobStatusRepository'
        );
        $this->app->bind(
            'vital40\Repositories\PermissionRepositoryInterface',
            'vital40\Repositories\DBPermissionRepository'
        );
        $this->app->bind(
            'vital40\Repositories\PermissionRoleRepositoryInterface',
            'vital40\Repositories\DBPermissionRoleRepository'
        );
        $this->app->bind(
            'vital40\Repositories\PurchaseOrderRepositoryInterface',
            'vital40\Repositories\DBPurchaseOrderRepository'
        );
        $this->app->bind(
            'vital40\Repositories\PurchaseOrderDetailRepositoryInterface',
            'vital40\Repositories\DBPurchaseOrderDetailRepository'
        );
        $this->app->bind(
            'vital40\Repositories\ReceiptHistoryRepositoryInterface',
            'vital40\Repositories\DBReceiptHistoryRepository'
        );
        $this->app->bind(
            'vital40\Repositories\RoleRepositoryInterface',
            'vital40\Repositories\DBRoleRepository'
        );
        $this->app->bind(
            'vital40\Repositories\RoleUserRepositoryInterface',
            'vital40\Repositories\DBRoleUserRepository'
        );
        $this->app->bind(
            'vital40\Repositories\SessionTypeRepositoryInterface',
            'vital40\Repositories\DBSessionTypeRepository'
        );
        $this->app->bind(
            'vital40\Repositories\ToteRepositoryInterface',
            'vital40\Repositories\DBToteRepository'
        );
        $this->app->bind(
            'vital40\Repositories\UPCRepositoryInterface',
            'vital40\Repositories\DBUPCRepository'
        );
        $this->app->bind(
            'vital40\Repositories\UserRepositoryInterface',
            'vital40\Repositories\DBUserRepository'
        );
        $this->app->bind(
            'vital40\Repositories\UserActivityRepositoryInterface',
            'vital40\Repositories\DBUserActivityRepository'
        );
        $this->app->bind(
            'vital40\Repositories\UserConversationRepositoryInterface',
            'vital40\Repositories\DBUserConversationRepository'
        );
        $this->app->bind(
            'vital40\Repositories\VendorComplianceRepositoryInterface',
            'vital40\Repositories\DBVendorComplianceRepository'
        );
    }

    /**
	 * Register the Article flow instance.
	 *
	 * @return void
	 */
	protected function registerArticleFlow()
	{
		$this->app->bindShared('receiveArticle', function($app)
		{
			return $this->app->make('App\vital40\Receive\ArticleFlow');
		});
	}

    /**
	 * Register the Location flow instance.
	 *
	 * @return void
	 */
	protected function registerLocationFlow()
	{
		$this->app->bindShared('receiveLocation', function($app)
		{
			return $this->app->make('App\vital40\Receive\LocationFlow');
		});
	}

    /**
     * Register the PurchaseOrder Reconciliation flow instance.
     *
     * @return void
     */
    protected function registerPOReconciliation()
    {
        $this->app->bindShared('poReconciliation', function($app)
        {
            return $this->app->make('App\vital40\Receive\POReconciliationFlow');
        });
    }

    /**
	 * Register the PurchaseOrder flow instance.
	 *
	 * @return void
	 */
	protected function registerPurchaseOrderFlow()
	{
		$this->app->bindShared('receivePO', function($app)
		{
			return $this->app->make('App\vital40\Receive\PurchaseOrderFlow');
		});
	}

}
