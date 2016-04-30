@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">News</div>

                <div class="panel-body">
                    <p><b>Watch here for application news!</b>
                    <p>This release contains these new features;
                    <ul>
                        <li>Quick Receive, Improved scan UPC/tote transaction process</li>
                        @if(Entrust::hasRole(['teamLead', 'super', 'manager', 'support']))
                            <li>Reports, new Rework Report, save in csv or Excel formats</li>
                        @endif
                        @if(Entrust::hasRole(['admin', 'support']))
                            <li>Admin menu, improved Permissions and Roles screens with Create, Edit and Delete icons.</li>
                            <li>Admin menu, new RolePermissions screen to manage permissions related to roles.</li>
                            <li>Admin menu, improved Users screen with Create, Edit and Delete icons.</li>
                            <li>Admin menu, new UserRoles screen to manage roles related to users.</li>
                        @endif
                        @if(Entrust::hasRole(['teamLead', 'super', 'manager', 'support']))
                            <li>"Forget Your Password?" on Login page will email to you a link that can change your password.</li>
                        @endif
                        @if(Entrust::hasRole(['manager', 'support']))
                            <li>Enhanced Location, Pallet, Tote and Inventory management screens.</li>
                        @endif
                    </ul>
                    <p>Previous release features;
                    <ul>
                        <li>Quick Receive, Select a Location now has page forward</li>
                        <li>Quick Receive, Close tote now displays quantity in pick face locations, click on Pick Face location button</li>
                        <li>Quick Receive, UPC Grid lines showing expected vs received can hide, click on UPC Grid Lines button</li>
                        <li>Quick Receive, comingled article, scanning UPC message now indicates the tote to use</li>
                        <li>Quick Receive, shows only open totes for each UPC</li>
                        @if(Entrust::hasRole(['teamLead', 'super', 'manager', 'support']))
                            <li>Lists such as Article & UPC, have Create, Edit and Delete buttons (for those with authorization)</li>
                            <li>POReconciliation now has paging of PO details</li>
                            <li>POReconciliation now has export to Excel or CSV formats</li>
                            <li>Article & UPC filters now remember your previous filter values.</li>
                            <li>UPC list now shows parent Articles and Quantities.</li>
                        @endif
                    </ul>
                    <p>Existing features;
                    <ul>
                        <li>List menu item show your Activities, Conversations.</li>
                        @if(Entrust::hasRole(['teamLead', 'super', 'manager', 'support']))
                            <li>Inquiry menu: Productivity Numbers show counts within a time frame, by hour, for the entire eCom</li>
                            <li>Inquiry menu: Associate Numbers show counts within a time frame, by associate</li>
                            <li>Many date and time fields now have a drop down date time picker to ease date and time entry</li>
                            <li>Productivity and Associate Numbers screens enable export to Excel or to comma delimited files.</li>
                        @endif
                        <li>Links at the bottom of each list, look for links to page 2,3,4, ..</li>
                        <li>QuickReceive: bottom left shows receiving progress for scanned UPCs within selected Article</li>
                        @if(Entrust::hasRole(['teamLead', 'super', 'manager', 'support']))
                            <li>Receiving performance tracking numbers are recorded</li>
                        @endif
                        @if(Entrust::hasRole(['admin', 'support']))
                            <li>Admin menu option now lists permissions, roles and users. This is the first step toward providing user password change, user permissions and roles maintenance.</li>
                        @endif
                        <li>On the right, under your name, you may view your Numbers.</li>
                    </ul>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
