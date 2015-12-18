@extends('pages.panelList')

@section('title')
    <!-- section('title') of pages/purchaseOrder/show.blade.php  -->

    @lang('labels.titles.PurchaseOrder_for') {{ $purchaseOrder->Order_Number }}

    <!-- stop of pages/purchaseOrder/show.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/purchaseOrder/show.blade.php  -->

    @lang('labels.titles.PurchaseOrder')

    <!-- stop of pages/purchaseOrder/show.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/purchaseOrder/show.blade.php  -->

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

    <!-- stop of pages/purchaseOrder/show.blade.php, section('form') -->
@stop

@section('list')
    <!-- section('list') of pages/purchaseOrder/show.blade.php  -->

    @if(isset($purchaseOrderDetails) && count($purchaseOrderDetails))
        <h3>{!! Lang::get('labels.titles.PurchaseOrderDetails') !!}</h3>

        <!-- reuse pages.purchaseOrderDetail.list -->
        @include('pages.purchaseOrderDetail.list', ['hideOrderNumber' => 'true'])
    @endif

    <!-- stop of pages/purchaseOrder/show.blade.php, section('list') -->
@stop

