<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//Route::get('/', 'WelcomeController@index');
Route::get('/', 'VITaL40Controller@index');

Route::get('home', ['as' => 'home', 'uses' => 'HomeController@index']);

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::get('about', 'PagesController@about');

Route::get('contact', 'PagesController@contact');

// using the resources approach
//Route::resource('inboundOrder', 'vital3\InboundOrderController');
/* generates these routes
> php artisan route:list
+--------+-----------+-----------------------------------+----------------------+-------------------------------------------------------------+------------+
| Domain | Method    | URI                               | Name                 | Action                                                      | Middleware |
+--------+-----------+-----------------------------------+----------------------+-------------------------------------------------------------+------------+
|        | GET|HEAD  | inboundOrder                      | inboundOrder.index   | App\Http\Controllers\vital3\InboundOrderController@index    |            |
|        | GET|HEAD  | inboundOrder/create               | inboundOrder.create  | App\Http\Controllers\vital3\InboundOrderController@create   |            |
|        | POST      | inboundOrder                      | inboundOrder.store   | App\Http\Controllers\vital3\InboundOrderController@store    |            |
|        | GET|HEAD  | inboundOrder/{inboundOrder}       | inboundOrder.show    | App\Http\Controllers\vital3\InboundOrderController@show     |            |
|        | GET|HEAD  | inboundOrder/{inboundOrder}/edit  | inboundOrder.edit    | App\Http\Controllers\vital3\InboundOrderController@edit     |            |
|        | PUT       | inboundOrder/{inboundOrder}       | inboundOrder.update  | App\Http\Controllers\vital3\InboundOrderController@update   |            |
|        | PATCH     | inboundOrder/{inboundOrder}       |                      | App\Http\Controllers\vital3\InboundOrderController@update   |            |
|        | DELETE    | inboundOrder/{inboundOrder}       | inboundOrder.destroy | App\Http\Controllers\vital3\InboundOrderController@destroy  |            |
+--------+-----------+-----------------------------------+----------------------+-------------------------------------------------------------+------------+
*/

// using a get/post approach
//Route::get('InboundOrder', 'vital3\InboundOrderController@index');
//Route::get('InboundOrder/create', 'vital3\InboundOrderController@create');
// wildcard /{id} must come after the /create route definition, or  'create' will be considered the value for {id}
//Route::get('InboundOrder/{id}', 'vital3\InboundOrderController@show');
//Route::post('InboundOrder', 'vital3\InboundOrderController@store');
//Route::get('InboundOrder/{id}/edit', 'vital3\InboundOrderController@edit');

// Resources
Route::resource('article', 'vital40\ArticleController');
Route::patch('article', ['as' => 'article.filter', 'uses' => 'vital40\ArticleController@filter']);
Entrust::routeNeedsRole('article*', ['teamLead','super','manager'], Redirect::to('home'), false);
Entrust::routeNeedsRole('article/create', 'manager', Redirect::to('home'));
Entrust::routeNeedsRole('article/*/edit', 'manager', Redirect::to('home'));

Route::get('associateNumber', ['as' => 'associateNumber.index', 'uses' => 'AssociateNumberController@index']);
Route::patch('associateNumber', ['as' => 'associateNumber.filter', 'uses' => 'AssociateNumberController@filter']);
Route::patch('associateNumber/export', ['as' => 'associateNumber.export', 'uses' => 'AssociateNumberController@export']);
Route::get('associateNumber/{id}', ['as' => 'associateNumber.show', 'uses' => 'AssociateNumberController@show']);
Entrust::routeNeedsRole('associateNumber', ['teamLead','super','manager','support'], Redirect::to('home'), false);
Entrust::routeNeedsRole('associateNumber/export', ['teamLead','super','manager','support'], Redirect::to('home'), false);

Route::resource('inboundOrder', 'vital3\InboundOrderController');
Route::patch('inboundOrder', ['as' => 'inboundOrder.filter', 'uses' => 'vital3\InboundOrderController@filter']);
Entrust::routeNeedsRole('inboundOrder*', 'support', Redirect::to('home'), false);

Route::resource('inboundOrderDetail', 'vital3\InboundOrderDetailController');
Route::patch('inboundOrderDetail', ['as' => 'inboundOrderDetail.filter', 'uses' => 'vital3\InboundOrderDetailController@filter']);

Route::resource('inventory', 'InventoryController');
Route::patch('inventory', ['as' => 'inventory.filter', 'uses' => 'InventoryController@filter']);
Entrust::routeNeedsRole('inventory*', ['teamLead','super','manager','support'], Redirect::to('home'), false);
Entrust::routeNeedsRole('inventory/create', 'support', Redirect::to('home'));
Entrust::routeNeedsRole('inventory/*/edit', 'support', Redirect::to('home'));

Route::get('invSummary', ['as' => 'invSummary.index', 'uses' => 'InventorySummaryController@index']);
Route::patch('invSummary', ['as' => 'invSummary.filter', 'uses' => 'InventorySummaryController@filter']);
Route::patch('invSummary/export', ['as' => 'invSummary.export', 'uses' => 'InventorySummaryController@export']);
Route::get('invSummary/{id}', ['as' => 'invSummary.show', 'uses' => 'InventorySummaryController@show']);
Route::get('invSummary/{id}/details', ['as' => 'invSummary.details', 'uses' => 'InventorySummaryController@details']);
Entrust::routeNeedsRole('invSummary*', ['teamLead','super','manager','support'], Redirect::to('home'), false);

Route::resource('location', 'LocationController');
Route::patch('location', ['as' => 'location.filter', 'uses' => 'LocationController@filter']);
Entrust::routeNeedsRole('location*', ['teamLead','super','manager','support'], Redirect::to('home'), false);
Entrust::routeNeedsRole('location/create', 'manager', Redirect::to('home'));
Entrust::routeNeedsRole('location/*/edit', 'manager', Redirect::to('home'));

Route::resource('pallet', 'PalletController');
Route::patch('pallet', ['as' => 'pallet.filter', 'uses' => 'PalletController@filter']);
Entrust::routeNeedsRole('pallet*', ['teamLead','super','manager','support'], Redirect::to('home'), false);
Entrust::routeNeedsRole('pallet/create', 'manager', Redirect::to('home'));
Entrust::routeNeedsRole('pallet/*/edit', 'manager', Redirect::to('home'));

Route::resource('performanceTally', 'PerformanceTallyController');
Route::patch('performanceTally', ['as' => 'performanceTally.filter', 'uses' => 'PerformanceTallyController@filter']);
Entrust::routeNeedsRole('performanceTally*', ['teamLead','super','manager','support'], Redirect::to('home'), false);
Entrust::routeNeedsRole('performanceTally/create', 'manager', Redirect::to('home'));
Entrust::routeNeedsRole('performanceTally/*/edit', 'manager', Redirect::to('home'));

Route::resource('permission', 'PermissionController');
Route::patch('permission', ['as' => 'permission.filter', 'uses' => 'PermissionController@filter']);
Entrust::routeNeedsRole('permission*', ['teamLead','super','manager','support'], Redirect::to('home'), false);
Entrust::routeNeedsRole('permission/create', 'manager', Redirect::to('home'));
Entrust::routeNeedsRole('permission/*/edit', 'manager', Redirect::to('home'));

Route::get('po', ['as' => 'po.index', 'uses' => 'vital40\PurchaseOrderController@index']);
Route::patch('po', ['as' => 'po.filter', 'uses' => 'vital40\PurchaseOrderController@filter']);
Route::get('po/{id}', ['as' => 'po.show', 'uses' => 'vital40\PurchaseOrderController@show']);
Route::get('purchaseOrder', ['as' => 'purchaseOrder.index', 'uses' => 'vital40\PurchaseOrderController@index']);
Route::patch('purchaseOrder', ['as' => 'purchaseOrder.filter', 'uses' => 'vital40\PurchaseOrderController@filter']);
Route::get('purchaseOrder/{id}', ['as' => 'purchaseOrder.show', 'uses' => 'vital40\PurchaseOrderController@show']);
Entrust::routeNeedsRole('po*', ['teamLead','super','manager','support'], Redirect::to('home'), false);
Entrust::routeNeedsRole('purchaseOrder*', ['teamLead','super','manager','support'], Redirect::to('home'), false);

Route::get('pod', ['as' => 'pod.index', 'uses' => 'vital40\PurchaseOrderDetailController@index']);
Route::patch('pod', ['as' => 'pod.filter', 'uses' => 'vital40\PurchaseOrderDetailController@filter']);
Route::get('pod/{id}', ['as' => 'pod.show', 'uses' => 'vital40\PurchaseOrderDetailController@show']);
Route::get('purchaseOrderDetail', ['as' => 'purchaseOrderDetail.index', 'uses' => 'vital40\PurchaseOrderDetailController@index']);
Route::patch('purchaseOrderDetail', ['as' => 'purchaseOrderDetail.filter', 'uses' => 'vital40\PurchaseOrderDetailController@filter']);
Route::get('purchaseOrderDetail/{id}', ['as' => 'purchaseOrderDetail.show', 'uses' => 'vital40\PurchaseOrderDetailController@show']);

Route::get('productivityNumber', ['as' => 'productivityNumber.index', 'uses' => 'ProductivityNumberController@index']);
Route::patch('productivityNumber', ['as' => 'productivityNumber.filter', 'uses' => 'ProductivityNumberController@filter']);
Route::patch('productivityNumber/export', ['as' => 'productivityNumber.export', 'uses' => 'ProductivityNumberController@export']);
Entrust::routeNeedsRole('productivityNumber*', ['teamLead','super','manager','support'], Redirect::to('home'), false);
Entrust::routeNeedsRole('productivityNumber/create', ['support'], Redirect::to('home'), false);
Entrust::routeNeedsRole('productivityNumber/*/edit', ['support'], Redirect::to('home'), false);

Route::resource('receiptHistory', 'ReceiptHistoryController');
Route::patch('receiptHistory', ['as' => 'receiptHistory.filter', 'uses' => 'ReceiptHistoryController@filter']);
Entrust::routeNeedsRole('receiptHistory/create', ['support'], Redirect::to('home'), false);
Entrust::routeNeedsRole('receiptHistory/*/edit', ['support'], Redirect::to('home'), false);

Route::resource('role', 'RoleController');
Route::patch('role', ['as' => 'role.filter', 'uses' => 'RoleController@filter']);
Entrust::routeNeedsRole('role*', ['teamLead','super','manager','support'], Redirect::to('home'), false);
Entrust::routeNeedsRole('role/create', 'manager', Redirect::to('home'));
Entrust::routeNeedsRole('role/*/edit', 'manager', Redirect::to('home'));

Route::resource('rolePermissions', 'RolePermissionsController');
Route::patch('rolePermissions', ['as' => 'rolePermissions.filter', 'uses' => 'RolePermissionsController@filter']);
Entrust::routeNeedsRole('rolePermissions*', ['teamLead','super','manager','support'], Redirect::to('home'), false);
Entrust::routeNeedsRole('rolePermissions/create', 'manager', Redirect::to('home'));
Entrust::routeNeedsRole('rolePermissions/*/edit', 'manager', Redirect::to('home'));

Route::resource('tote', 'ToteController');
Route::patch('tote', ['as' => 'tote.filter', 'uses' => 'ToteController@filter']);
Entrust::routeNeedsRole('tote*', ['teamLead','super','manager','support'], Redirect::to('home'), false);
Entrust::routeNeedsRole('tote/create', 'manager', Redirect::to('home'));
Entrust::routeNeedsRole('tote/*/edit', 'manager', Redirect::to('home'));

Route::resource('upc', 'vital40\UPCController');
Route::patch('upc', ['as' => 'upc.filter', 'uses' => 'vital40\UPCController@filter']);
Entrust::routeNeedsRole('upc*', ['teamLead','super','manager'], Redirect::to('home'), false);
Entrust::routeNeedsRole('upc/create', 'manager', Redirect::to('home'));
Entrust::routeNeedsRole('upc/*/edit', 'manager', Redirect::to('home'));

Route::resource('user', 'UserController');
Route::patch('user', ['as' => 'user.filter', 'uses' => 'UserController@filter']);
//Entrust::routeNeedsRole('user*', ['teamLead','super','manager','support'], Redirect::to('home'), false);
Entrust::routeNeedsRole('user/create', 'manager', Redirect::to('home'));
Entrust::routeNeedsRole('user/*/edit', 'manager', Redirect::to('home'));

Route::resource('userActivity', 'UserActivityController');
Route::patch('userActivity', ['as' => 'userActivity.filter', 'uses' => 'UserActivityController@filter']);
Entrust::routeNeedsRole('userActivity/create', ['teamLead','super','manager'], Redirect::to('home'), false);
Entrust::routeNeedsRole('userActivity/*/edit', ['teamLead','super','manager'], Redirect::to('home'), false);

Route::resource('userConversation', 'UserConversationController');
Route::patch('userConversation', ['as' => 'userConversation.filter', 'uses' => 'UserConversationController@filter']);
Entrust::routeNeedsRole('userConversation/create', ['support'], Redirect::to('home'), false);
Entrust::routeNeedsRole('userConversation/*/edit', ['support'], Redirect::to('home'), false);

//   Business UI
// Quick Receive
Route::get('quickReceive', ['as' => 'quickReceive.index', 'uses' => 'Receive\QuickReceiveController@index']);
Route::patch('quickReceive', ['as' => 'quickReceive.filter', 'uses' => 'Receive\QuickReceiveController@filter']);
Route::get('quickReceive/{id}', ['as' => 'quickReceive.show', 'uses' => 'Receive\QuickReceiveController@show']);
Route::post('quickReceive/upcGridLines', ['as' => 'quickReceive.upcGridLines', 'uses' => 'Receive\QuickReceiveController@upcGridLines']);
Route::post('quickReceive/pickFaceLines', ['as' => 'quickReceive.pickFaceLines', 'uses' => 'Receive\QuickReceiveController@pickFaceLines']);
Route::post('quickReceive/Texting', ['as' => 'quickReceive.texting', 'uses' => 'Receive\QuickReceiveController@texting']);
Entrust::routeNeedsRole('quickReceive*', ['receiver','teamLead','super','manager'], Redirect::to('home'), false);

// Receive Location
Route::get('receiveLocation', ['as' => 'receiveLocation.index', 'uses' => 'Receive\ReceiveLocationController@index']);
Route::post('receiveLocation', ['as' => 'receiveLocation.filter', 'uses' => 'Receive\ReceiveLocationController@filter']);
Route::get('receiveLocation/{id}', ['as' => 'receiveLocation.show', 'uses' => 'Receive\ReceiveLocationController@show']);
Route::put('receiveLocation/{id}', ['as' => 'receiveLocation.update', 'uses' => 'Receive\ReceiveLocationController@update']);

// Receive PO
Route::get('receivePO', ['as' => 'receivePO.index', 'uses' => 'Receive\ReceivePOController@index']);
Route::post('receivePO', ['as' => 'receivePO.filter', 'uses' => 'Receive\ReceivePOController@filter']);
Route::get('receivePO/{id}', ['as' => 'receivePO.show', 'uses' => 'Receive\ReceivePOController@show']);
Route::put('receivePO/{id}', ['as' => 'receivePO.update', 'uses' => 'Receive\ReceivePOController@update']);
Entrust::routeNeedsRole('receive*', ['teamLead','super','manager'], Redirect::to('home'), false);

// PO Reconciliation
Route::get('poReconciliation', ['as' => 'poReconciliation.index', 'uses' => 'Receive\POReconciliationController@index']);
Route::post('poReconciliation', ['as' => 'poReconciliation.filter', 'uses' => 'Receive\POReconciliationController@filter']);
Route::get('poReconciliation/{id}', ['as' => 'poReconciliation.show', 'uses' => 'Receive\POReconciliationController@show']);
Route::get('poReconciliation/{id}/review', ['as' => 'poReconciliation.review', 'uses' => 'Receive\POReconciliationController@review']);
Route::post('poReconciliation/{id}/confirm', ['as' => 'poReconciliation.confirm', 'uses' => 'Receive\POReconciliationController@confirm']);
Route::patch('poReconciliation/{id}/export', ['as' => 'poReconciliation.export', 'uses' => 'Receive\POReconciliationController@export']);
//Route::post('poReconciliation/{id}', ['as' => 'poReconciliation.show', 'uses' => 'Receive\POReconciliationController@show']);
//Route::put('poReconciliation/{id}', ['as' => 'poReconciliation.update', 'uses' => 'Receive\POReconciliationController@update']);
Entrust::routeNeedsRole('poReconciliation', ['teamLead','super','manager'], Redirect::to('home'), false);

// Receive Article
Route::get('receiveArticle', ['as' => 'receiveArticle.index', 'uses' => 'Receive\ReceiveArticleController@index']);
Route::patch('receiveArticle', ['as' => 'receiveArticle.filter', 'uses' => 'Receive\ReceiveArticleController@filter']);
Route::get('receiveArticle/{id}', ['as' => 'receiveArticle.show', 'uses' => 'Receive\ReceiveArticleController@show']);
#Route::put('receiveArticle/{id}', ['as' => 'receiveArticle.update', 'uses' => 'Receive\ReceiveArticleController@update']);
#Route::post('receiveArticle/{id}', ['as' => 'receiveArticle.create', 'uses' => 'Receive\ReceiveArticleController@create']);
Route::post('receiveArticle/refresh', ['as' => 'receiveArticle.refresh', 'uses' => 'Receive\ReceiveArticleController@refresh']);
Route::post('receiveArticle/texting', ['as' => 'receiveArticle.texting', 'uses' => 'Receive\ReceiveArticleController@texting']);

//   Reports
// Rework Report
Route::get('reworkReport', ['as' => 'reworkReport.index', 'uses' => 'ReworkReportController@index']);
Route::patch('reworkReport', ['as' => 'reworkReport.filter', 'uses' => 'ReworkReportController@filter']);
Route::patch('reworkReport/export', ['as' => 'reworkReport.export', 'uses' => 'ReworkReportController@export']);

