@extends('pages.panelList')

@section('title')
    <!-- section('title') of pages/inboundOrder/show.blade.php  -->

    @lang('labels.titles.InboundOrder_for') {{ $inboundOrder->Order_Number }}

    <!-- stop of pages/inboundOrder/show.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/inboundOrder/show.blade.php  -->

    @lang('labels.titles.InboundOrder')

    <!-- stop of pages/inboundOrder/show.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/inboundOrder/show.blade.php  -->

    {{--
    * Table Structure
    * desc Inbound_Order;
    +----------------------+-------------+------+-----+---------+-------+
    | Field                | Type        | Null | Key | Default | Extra |
    +----------------------+-------------+------+-----+---------+-------+
    | objectID             | bigint(20)  | NO   | PRI | NULL    |       |
    | Order_Number         | varchar(85) | YES  |     | NULL    |       |
    | Client               | varchar(85) | YES  |     | NULL    |       |
    | Purchase_Order       | varchar(85) | YES  | MUL | NULL    |       |
    | Invoice_Number       | varchar(85) | YES  |     | NULL    |       |
    | Status               | varchar(85) | YES  | MUL | NULL    |       |
    | Created              | varchar(85) | YES  |     | NULL    |       |
    | Expected             | varchar(85) | YES  |     | NULL    |       |
    +----------------------+-------------+------+-----+---------+-------+
    --}}

    @include('fields.textList', ['fieldName' => 'objectID'      , 'fieldValue' => $inboundOrder->objectID         ])
    @include('fields.textList', ['fieldName' => 'Order_Number'  , 'fieldValue' => $inboundOrder->Order_Number     ])
    @include('fields.textList', ['fieldName' => 'Client'        , 'fieldValue' => $clients[$inboundOrder->Client] ])
    @include('fields.textList', ['fieldName' => 'Purchase_Order', 'fieldValue' => $inboundOrder->Purchase_Order, 'urlName' => 'purchaseOrder.show'])
    @include('fields.textList', ['fieldName' => 'Invoice_Number', 'fieldValue' => $inboundOrder->Invoice_Number   ])
    @include('fields.textList', ['fieldName' => 'Status'        , 'fieldValue' => Lang::get('lists.inboundOrder.status.'.$inboundOrder->Status) ])
    @include('fields.textList', ['fieldName' => 'Created'       , 'fieldValue' => $inboundOrder->Created          ])
    @include('fields.textList', ['fieldName' => 'Expected'      , 'fieldValue' => $inboundOrder->Expected         ])

    <!-- Inbound_Order_Additional -->
    @foreach($inboundOrderAdditional as $additional)
        @include('fields.textList', ['fieldNameText' => $additional->Name, 'fieldValue' => $additional->Value ])
    @endforeach

    <!-- stop of pages/inboundOrder/show.blade.php, section('form') -->
@stop

@section('list')
    <!-- section('list') of pages/inboundOrder/show.blade.php  -->

    @if(isset($inboundOrderDetails) && count($inboundOrderDetails))
        <h3>{!! Lang::get('labels.titles.InboundOrderDetails_for') !!} {{ $inboundOrder->Order_Number }}</h3>

        <!-- reuse pages.inboundOrderDetail.list -->
        @include('pages.inboundOrderDetail.list', ['hideOrderNumber' => 'true'])
    @endif

    <!-- stop of pages/inboundOrder/show.blade.php, section('list') -->
@stop

