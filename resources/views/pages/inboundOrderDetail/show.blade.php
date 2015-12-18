@extends('pages.panel')

@section('panel')
    <!-- section('panel') of pages/inboundOrderDetail/show.blade.php  -->

    <div class="panel-heading">@lang('labels.titles.InboundOrderDetail_for') {{ $inboundOrderDetail->Order_Number }}</div>
    <div class="panel-body">

        @include('errors.list')

        {{--
        * Table Structure
        * desc Inbound_Order_Detail;
        +--------------+-------------+------+-----+---------+-------+
        | Field        | Type        | Null | Key | Default | Extra |
        +--------------+-------------+------+-----+---------+-------+
        | objectID     | bigint(20)  | NO   | PRI | NULL    |       |
        | Order_Number | varchar(85) | YES  | MUL | NULL    |       | contains InboundOrder.objectID
        | SKU          | varchar(85) | YES  | MUL | NULL    |       | contains Article.objectID
        | Expected_Qty | varchar(85) | YES  |     | NULL    |       |
        | Actual_Qty   | varchar(85) | YES  |     | NULL    |       |
        | Status       | varchar(85) | YES  | MUL | NULL    |       |
        | UPC          | varchar(35) | YES  | MUL | NULL    |       |
        | UOM          | varchar(85) | YES  |     |         |       | contains UOM.objectID
        +--------------+-------------+------+-----+---------+-------+
        --}}

        @include('fields.textList', ['fieldName' => 'objectID'     , 'fieldValue' => $inboundOrderDetail->objectID     ])
        @include('fields.textList', ['fieldName' => 'Order_Number' , 'fieldValue' => $inboundOrderDetail->Order_Number, 'urlName' => 'inboundOrder.show'])
        @include('fields.textList', ['fieldName' => 'SKU'          , 'fieldValue' => $inboundOrderDetail->SKU         , 'urlName' => 'article.show'])
        @include('fields.textList', ['fieldName' => 'Expected_Qty' , 'fieldValue' => $inboundOrderDetail->Expected_Qty ])
        @include('fields.textList', ['fieldName' => 'Actual_Qty'   , 'fieldValue' => $inboundOrderDetail->Actual_Qty   ])
        @include('fields.textList', ['fieldName' => 'Status'       , 'fieldValue' => Lang::get('lists.inboundOrderDetail.status.'.$inboundOrderDetail->Status) ])
        @include('fields.textList', ['fieldName' => 'UPC'          , 'fieldValue' => $inboundOrderDetail->UPC          ])
        @include('fields.textList', ['fieldName' => 'UOM'          , 'fieldValue' => $uoms[$inboundOrderDetail->UOM]   ])

    </div>

    <!-- stop of pages/inboundOrderDetail/show.blade.php, section('panel') -->
@stop

