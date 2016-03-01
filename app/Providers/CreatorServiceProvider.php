<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use View;

class CreatorServiceProvider extends ServiceProvider {

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        /*
         * see: http://stackoverflow.com/questions/19265237/difference-between-view-composer-and-creator-in-laravel-4
         *
         * Order of invocation;
         * 1. ViewCreator variable are applied to the view
         * 2. Controllers View::make(..[variables]) command variables are applied to the view
         * 3. ViewComposer variable are applied to the view.
         *
         * Note: a ViewCreator is invoked before the Controllers View::make(..[variables]) command variables are applied.
         * Therefor a Controller can override the variable provided by a ViewCreator
         * Contrast: a ViewComposer is invoked after the Controllers View::make(..[variables]) command variables are applied.
         * Therefor a ViewComposer overrides the variable provided by a Controller
         */

        /* Export file types */
        View::creator([
            'pages.associateNumber.index',
            'pages.poReconciliation.show',
            'pages.productivityNumber.index',
            'pages.reworkReport.index',
        ], 'App\Http\ViewCreators\ExportTypeCreator');

        /* Clients */
        View::creator([
            'pages.article.create',
            'pages.article.edit',
            'pages.article.index',
            'pages.article.show',
            'pages.inboundOrder.create',
            'pages.inboundOrder.edit',
            'pages.inboundOrder.index',
            'pages.inboundOrder.show',
            'pages.poReconciliation.index',
            'pages.poReconciliation.show',
            'pages.purchaseOrder.index',
            'pages.purchaseOrder.show',
            'pages.receivePO.index',
            'pages.receivePO.show',
            'pages.upc.create',
            'pages.upc.edit',
            'pages.upc.index',
            'pages.upc.show',
        ], 'App\Http\ViewCreators\ClientCreator');

        /* Permissions */
        View::creator([
            'pages.rolePermissions.index',
            'pages.rolePermissions.show',
        ], 'App\Http\ViewCreators\PermissionCreator');

        /* Roles */
        View::creator([
            'pages.rolePermissions.create',
            'pages.rolePermissions.edit',
            'pages.rolePermissions.index',
            'pages.rolePermissions.show',
            'pages.rolePermissions.update',
            'pages.userRoles.create',
            'pages.userRoles.edit',
            'pages.userRoles.index',
            'pages.userRoles.show',
            'pages.userRoles.update',
        ], 'App\Http\ViewCreators\RoleCreator');

        /* UOMs */
        View::creator([
            'pages.article.create',
            'pages.article.edit',
            'pages.article.index',
            'pages.article.show',
            'pages.inboundOrder.show',
            'pages.inboundOrderDetail.create',
            'pages.inboundOrderDetail.edit',
            'pages.inboundOrderDetail.index',
            'pages.inboundOrderDetail.show',
            'pages.inventory.create',
            'pages.inventory.edit',
            'pages.inventory.index',
            'pages.inventory.show',
            'pages.invSummary.index',
            'pages.invSummary.show',
            'pages.poReconciliation.excel',
            'pages.poReconciliation.show',
            'pages.purchaseOrder.show',
            'pages.purchaseOrderDetail.index',
            'pages.purchaseOrderDetail.show',
            'pages.quickReceive.index',
            'pages.receivePO.index',
            'pages.receivePO.show',
            'pages.tote.show',
            'pages.upc.create',
            'pages.upc.edit',
            'pages.upc.index',
            'pages.upc.show',
        ], 'App\Http\ViewCreators\UOMCreator');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

}