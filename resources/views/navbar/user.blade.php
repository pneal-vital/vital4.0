
<!-- Beginning of navbar/user.blade.php -->

@inject('carbon', 'Carbon\Carbon')

{{-- in here is_null(\Auth::user()) == false --}}
<nav class="navbar">
    <div class="container-fluid" id="narbar">
        <div class="navbar-header" id="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#userNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ URL::route('home') }}"><b>VITaL 4.0</b></a>
        </div>
        <div class="collapse navbar-collapse" id="userNavbar">
            <ul class="nav nav-pills navbar-nav">
                <li>{!! link_to_route('home', Lang::get('labels.navbar.Home')) !!}</li>
                {{-- TODO: Add a new Inquiry menu item which should include hierarchy, .. --}}
                @if(Entrust::hasRole(['teamLead', 'super', 'manager', 'support']))
                    <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">@lang('labels.navbar.Inquiry') <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            @if(Entrust::can('associateNumber.view'))
                                <li>{!! link_to_route('associateNumber.index', Lang::get('labels.navbar.AssociateNumbers')) !!}</li>
                            @endif
                            <li>{!! link_to_route('productivityNumber.index', Lang::get('labels.navbar.ProductivityNumbers')) !!}</li>
                            <li class="divider"></li>
                            <li>{!! link_to_route('invSummary.index', Lang::get('labels.navbar.InventorySummary')) !!}</li>
                        </ul>
                    </li>
                @endif
                @if(Entrust::hasRole(['teamLead', 'super', 'manager', 'support']) or Entrust::can(['userActivity.view', 'userConversation.view']))
                    <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">@lang('labels.navbar.List') <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            @if(Entrust::hasRole(['teamLead', 'super', 'manager', 'support']))
                                <li>{!! link_to_route('article.index', Lang::get('labels.navbar.Articles')) !!}</li>
                            @endif
                            @if(Entrust::hasRole(['support']))
                                <li>{!! link_to_route('inboundOrder.index', Lang::get('labels.navbar.InboundOrders')) !!}</li>
                                <li>{!! link_to_route('inboundOrderDetail.index', Lang::get('labels.navbar.InboundOrderDetails')) !!}</li>
                            @endif
                            @if(Entrust::hasRole(['teamLead', 'super', 'manager', 'support']))
                                <li>{!! link_to_route('inventory.index', Lang::get('labels.navbar.Inventories')) !!}</li>
                                <li>{!! link_to_route('location.index', Lang::get('labels.navbar.Locations')) !!}</li>
                                <li>{!! link_to_route('pallet.index', Lang::get('labels.navbar.Pallets')) !!}</li>
                                <li>{!! link_to_route('performanceTally.index', Lang::get('labels.navbar.PerformanceTallies')) !!}</li>
                                <li>{!! link_to_route('po.index', Lang::get('labels.navbar.PurchaseOrders')) !!}</li>
                                <li>{!! link_to_route('pod.index', Lang::get('labels.navbar.PurchaseOrderDetails')) !!}</li>
                                <li>{!! link_to_route('receiptHistory.index', Lang::get('labels.navbar.ReceiptHistories')) !!}</li>
                                <li>{!! link_to_route('tote.index', Lang::get('labels.navbar.Totes')) !!}</li>
                                <li>{!! link_to_route('upc.index', Lang::get('labels.navbar.UPCs')) !!}</li>
                            @endif
                            @if(Entrust::can('userActivity.view'))
                                <li>{!! link_to_route('userActivity.index', Lang::get('labels.navbar.UserActivities')) !!}</li>
                            @endif
                            @if(Entrust::can('userConversation.view'))
                                <li>{!! link_to_route('userConversation.index', Lang::get('labels.navbar.UserConversations')) !!}</li>
                            @endif
                        </ul>
                    </li>
                @endif
                {{-- TODO: New menu item should be replaced with [add], [update], [delete] buttons/icons on the various List screens  --}}
                @if(Entrust::hasRole(['support']))
                    <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">@lang('labels.navbar.New') <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            @if(Entrust::hasRole(['support']))
                                <li>{!! link_to_route('inboundOrder.create', Lang::get('labels.navbar.InboundOrder')) !!}</li>
                                <li>{!! link_to_route('inboundOrderDetail.create', Lang::get('labels.navbar.InboundOrderDetail')) !!}</li>
                            @endif
                            <li>{!! link_to_route('inventory.create', Lang::get('labels.navbar.Inventory')) !!}</li>
                            <li>{!! link_to_route('location.create', Lang::get('labels.navbar.Location')) !!}</li>
                            <li>{!! link_to_route('pallet.create', Lang::get('labels.navbar.Pallet')) !!}</li>
                            <li>{!! link_to_route('performanceTally.create', Lang::get('labels.navbar.PerformanceTally')) !!}</li>
                            @if(Entrust::hasRole(['support']))
                                <li>{!! link_to_route('receiptHistory.create', Lang::get('labels.navbar.ReceiptHistory')) !!}</li>
                            @endif
                            <li>{!! link_to_route('tote.create', Lang::get('labels.navbar.Tote')) !!}</li>
                            <li>{!! link_to_route('userActivity.create', Lang::get('labels.navbar.UserActivity')) !!}</li>
                            @if(Entrust::hasRole(['support']))
                                <li>{!! link_to_route('userConversation.create', Lang::get('labels.navbar.UserConversation')) !!}</li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if(Entrust::hasRole(['receiver']))
                    <li>{!! link_to_route('quickReceive.index', Lang::get('labels.navbar.QuickReceive')) !!}</li>
                @endif
                @if(Entrust::hasRole(['poReconcile', 'teamLead', 'super', 'manager', 'support']))
                    <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">@lang('labels.navbar.Receive') <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li>{!! link_to_route('receiveLocation.index', Lang::get('labels.navbar.Receive_Locations')) !!}</li>
                            <li>{!! link_to_route('receivePO.index', Lang::get('labels.navbar.Receive_POs')) !!}</li>
                            <li>{!! link_to_route('receiveArticle.index', Lang::get('labels.navbar.Receive_Articles')) !!}</li>
                            @if(Entrust::hasRole(['poReconcile', 'support']))
                                <li>{!! link_to_route('poReconciliation.index', Lang::get('labels.navbar.PO_Reconciliation')) !!}</li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if(Entrust::can(['report.rework']))
                    <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">@lang('labels.navbar.Report') <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            @if(Entrust::can('report.rework'))
                                <li>{!! link_to_route('reworkReport.index', Lang::get('labels.navbar.Rework')) !!}</li>
                            @endif
                        </ul>
                    </li>
                @endif
            </ul>
            <ul class="nav nav-pills navbar-nav navbar-right">
                @if(Entrust::hasRole(['admin','support']))
                    <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">@lang('labels.navbar.Admin') <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li>{!! link_to_route('permission.index', Lang::get('labels.navbar.Permissions')) !!}</li>
                            <li>{!! link_to_route('role.index', Lang::get('labels.navbar.Roles')) !!}</li>
                            <li>{!! link_to_route('rolePermissions.index', Lang::get('labels.navbar.RolePermissions')) !!}</li>
                            <li>{!! link_to_route('user.index', Lang::get('labels.navbar.Users')) !!}</li>
                            <li>{!! link_to_route('userRoles.index', Lang::get('labels.navbar.UserRoles')) !!}</li>
                        </ul>
                    </li>
                @endif
                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user"></span>&nbsp; {{ \Auth::user()->name }}</a>
                    <ul class="dropdown-menu">
                        @if(Entrust::can(['userActivity.view', 'myActivity.view']))
                            <li>{!! link_to_route('userActivity.index', Lang::get('labels.navbar.MyActivities')) !!}</li>
                        @endif
                        @if(Entrust::can(['associateNumber.view', 'myNumber.view']))
                            <li>{!! link_to_route('associateNumber.show', Lang::get('labels.navbar.MyNumbers'),
                                ['userName' => Auth::user()->name, 'fromDate' => ($carbon::now()->subHours(10)->toDateTimeString()), 'toDate' => ($carbon::now()->toDateTimeString())]) !!}</li>
                        @endif
                        <li>{!! link_to_route('auth.changePassword', Lang::get('labels.navbar.ChangePassword')) !!}</li>
                            {{-- not sure why this next line does not work, compare this with
                                 resources/views/pages/performanceTally/list.blade.php:        <th>{!! Lang::get('labels.putAwayRec')     !!}</th>
                        <li>{!! link_to_route('auth.logout', Lang::get('labels.navbar.iLogout')) !!}</li>
                             --}}
                        <li><a href="/auth/logout"><span class="glyphicon glyphicon-log-out"></span>&nbsp; @lang('labels.navbar.Logout')</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- End of navbar/user.blade.php -->
