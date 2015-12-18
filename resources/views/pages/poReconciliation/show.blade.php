@extends('pages.panelList')

@section('title')
    <!-- section('title') of pages/poReconciliation/index.blade.php  -->

    @lang('labels.titles.PO_Reconciliation') {{ $purchaseOrder->Order_Number }}

    <!-- stop of pages/poReconciliation/index.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/poReconciliation/show.blade.php  -->

    @lang('labels.titles.PO_Reconciliation')

    <!-- stop of pages/poReconciliation/index.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/poReconciliation/show.blade.php  -->

    {{-- PurchaseOrder show fields --}}
    {!! Form::model($purchaseOrder, ['route' => ['poReconciliation.review', $purchaseOrder->Purchase_Order ], 'method' => 'get']) !!}

    {{--
    * Purchase Order;
    +----------------------+-------------+------+-----+---------+-------+
    | Field                | Type        | Null | Key | Default | Extra |
    +----------------------+-------------+------+-----+---------+-------+
    | Purchase_Order       | varchar(85) | YES  | MUL | NULL    |       |
    | objectID             | bigint(20)  | NO   | PRI | NULL    |       |
    | Order_Number         | varchar(85) | YES  |     | NULL    |       |
    | Client               | varchar(85) | YES  |     | NULL    |       |
    | Invoice_Number       | varchar(85) | YES  |     | NULL    |       |
    | Status               | varchar(85) | YES  | MUL | NULL    |       |
    | Created              | varchar(85) | YES  |     | NULL    |       |
    | Expected             | varchar(85) | YES  |     | NULL    |       |
    +----------------------+-------------+------+-----+---------+-------+
    --}}

    @include('fields.textList', ['fieldName' => 'Purchase_Order' , 'fieldValue' => $purchaseOrder->Purchase_Order   ])
    @if(Entrust::hasRole(['support']))
        @include('fields.textList', ['fieldName' => 'objectID'       , 'fieldValue' => $purchaseOrder->objectID         ])
        @include('fields.textList', ['fieldName' => 'Order_Number'   , 'fieldValue' => $purchaseOrder->Order_Number     ])
        @include('fields.textList', ['fieldName' => 'Client'         , 'fieldValue' => $clients[$purchaseOrder->Client] ])
        @include('fields.textList', ['fieldName' => 'Invoice_Number' , 'fieldValue' => $purchaseOrder->Invoice_Number   ])
    @endif
    @include('fields.textList', ['fieldName' => 'Status'         , 'fieldValue' => Lang::get('lists.purchaseOrder.status.'.$purchaseOrder->Status) ])
    @if(Entrust::hasRole(['support']))
        @include('fields.textList', ['fieldName' => 'Created'        , 'fieldValue' => $purchaseOrder->Created          ])
    @endif
    @include('fields.textList', ['fieldName' => 'Expected'       , 'fieldValue' => $purchaseOrder->Expected         ])

{{--
    <div class="form-group">
        <div class="col-md-4 col-md-offset-4">
            {!! Form::submit(Lang::get('labels.buttons.Show_all_details')   , ['id' => 'btn-Show_all_details'   , 'name' => 'btn_Show_all_details'   , 'class' => 'btn '.($completedArticles == 'Hide_Completed_Articles' ? 'btn-success' : 'btn-primary').' form-control']) !!}
        </div>
        <div class="col-md-4">
            {!! Form::submit(Lang::get('labels.buttons.Show_only_variances'), ['id' => 'btn-Show_only_variances', 'name' => 'btn_Show_only_variances', 'class' => 'btn '.($completedArticles == 'Show_Completed_Articles' ? 'btn-success' : 'btn-primary').' form-control']) !!}
        </div>
    </div>
--}}
    &nbsp;
    @if($purchaseOrder->Status == 'OPEN' || $purchaseOrder->Status == 'REC')
        <div class="form-group">
            <div class="col-md-6 col-md-offset-6">
                <button type="button" class="btn btn-primary" title="Click to confirm this Purchase Order" id="mobile" name="mobile" data-toggle="modal" data-target=".bs-example-modal-lg">Confirm Purchase Order {!! $purchaseOrder->Purchase_Order !!}</button>
            </div>
        </div>
    @elseif($purchaseOrder->Status == 'CONF')
        <p>Purchase Order already confirmed
    @else
        <p>Purchase Order status must be Open or Received
    @endif
    {!! Form::close() !!}

    <!-- Modal -->
    <div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Confirm the Purchase Order?</h4>
                </div>
                <div class="modal-body">
                    {!! Form::model($purchaseOrder, ['route' => ['poReconciliation.confirm', $purchaseOrder->Purchase_Order], 'method' => 'post', 'class' => 'form-horizontal']) !!}

{{--
                    <div class="form-group has-error">
                        <div class="col-sm-2">Sign here</div>
                        <div class="col-sm-8">
                            <input type="text" name="from" class="form-control" value="{{ \Auth::user()->name }}" disabled>
                            <div class="help-block">Are you sure you want to confirm this Purchase Order?
                            <p>You Know, once confirmed, there is no going back! No second chances. No take backs. No repeals. No rescind. No call offs.
                            <p>We have your number. Glen will call you! Better prepare your story now. The old "I thought he said I could" will not work with Glen.
                            <p>Are you 50% wondering this may be right? Logoff, go home, break out a beer, see you tomorrow.
                            <p>Are you 75% considering this is good? Must be lunch time, see you in an hour.
                            <p>Are you 90% convinced this is the best course of action? Time for a coffee break, see you in 15 minutes.
                            <p>Are you 95% certain you want to do this? Raise your hand, yell out "Glen, can I do this?", then reconsider.
                            <p>Are you 99% believing firmly in the truth of this action? Well then you have a tough decision to make.
                            </div>
                        </div>
                    </div>
--}}

                    <div class="modal-footer">
                        <!-- div class="col-sm-8 col-md-offset-1" -->
                        <div class="col-sm-5 col-md-offset-1">
                            <a href="{!! route('poReconciliation.review', [$purchaseOrder->Purchase_Order]) !!}">
                                {!! Html::image('img/Thumbs_Up.jpg', "Thumbs Up",array('height'=>'100','width'=>'120')) !!}
                            </a>
                            <button id="btn-No" name="btn_No" class="btn btn-primary" value="No">No I'd better not. (Press this button!)</button>
                        </div>
{{--
                    </div>

                    <div class="modal-footer">
                        <div class="col-sm-5">
                            <a href="{!! route('poReconciliation.review', [$purchaseOrder->Purchase_Order]) !!}">
                                {!! Html::image('img/Hmmm.jpg', "Hmmm",array('height'=>'100','width'=>'120')) !!}
                            </a>
                            <button id="btn-No" name="btn_No" class="btn btn-primary" value="No">99% believing firmly in the<br>truth of this action</button>
                        </div>
--}}

                        <!-- div class="col-sm-5 col-md-offset-2" -->
                        <div class="col-sm-5 col-md-offset-1">
                            <a href="{!! route('poReconciliation.confirm', [$purchaseOrder->Purchase_Order]) !!}">
                                {!! Html::image('img/Ohoh.jpg', "Ohoh",array('height'=>'100','width'=>'120','name'=>'img_Confirm')) !!}
                            </a>
                            <button id="btn-Confirm" name="btn_Confirm" class="btn btn-primary" value="Confirm">Confirming 100% confident<br>(Don't touch this button!)</button>
                        </div>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

    <!-- stop of pages/poReconciliation/show.blade.php, section('form') -->
@stop

@section('list')
    <!-- section('list') of pages/poReconciliation/show.blade.php  -->

    @if(isset($receiveArticles) && count($receiveArticles))
        <h3>{!! Lang::get('labels.titles.PO_Reconcile_Articles') !!}</h3>

        @include('pages.poReconciliation.list')
    @else
        <p>No variances to show
    @endif

    <!-- stop of pages/poReconciliation/show.blade.php, section('list') -->
@stop

@section('footer')
    <!-- section('footer') of pages/poReconciliation/index.blade.php  -->

    {{-- $exportTypes provided by App\Providers\CreatorServiceProvider --}}

    @if(count($receiveArticles))
        {{-- Export form --}}
        {!! Form::model($purchaseOrder, ['class' => 'form-horizontal', 'method' => 'patch', 'route' => ['poReconciliation.export', $purchaseOrder->objectID ]]) !!}

        @include('fields.ddList', ['fieldName' => 'ExportType', 'lists' => $exportTypes, 'fieldSize' => 'col-md-6'])

        @include('fields.button', ['submitButtonName' => 'PO_Reconciliation_Export'])

        {!! Form::close() !!}
    @endif

    <!-- stop of pages/poReconciliation/index.blade.php, section('footer') -->
@stop
