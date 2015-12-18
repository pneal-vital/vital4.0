@extends('pages.panel')

@section('panel')
    <!-- section('panel') of pages/purchaseOrderDetail/show.blade.php  -->

    <div class="panel-heading">@lang('labels.titles.PurchaseOrderDetail_for') {{ $purchaseOrderDetail->Order_Number }}</div>
    <div class="panel-body">

        @include('errors.list')

        {{--
        * PurchaseOrderDetail
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

        @if(Entrust::hasRole(['support']))
            @include('fields.textList', ['fieldName' => 'objectID'     , 'fieldValue' => $purchaseOrderDetail->objectID     ])
        @endif
        @include('fields.textList', ['fieldName' => 'Order_Number' , 'fieldValue' => $purchaseOrderDetail->Order_Number, 'urlName' => 'purchaseOrder.show'])
        @include('fields.textList', ['fieldName' => 'SKU'          , 'fieldValue' => $purchaseOrderDetail->SKU         , 'urlName' => 'article.show'])
        @include('fields.textList', ['fieldName' => 'Expected_Qty' , 'fieldValue' => $purchaseOrderDetail->Expected_Qty ])
        @include('fields.textList', ['fieldName' => 'Actual_Qty'   , 'fieldValue' => $purchaseOrderDetail->Actual_Qty   ])
        @include('fields.textList', ['fieldName' => 'Status'       , 'fieldValue' => $purchaseOrderDetail->Status       ])
        @include('fields.textList', ['fieldName' => 'UPC'          , 'fieldValue' => $purchaseOrderDetail->UPC          ])
        @include('fields.textList', ['fieldName' => 'UOM'          , 'fieldValue' => $uoms[$purchaseOrderDetail->UOM]   ])

    </div>

    <!-- stop of pages/purchaseOrderDetail/show.blade.php, section('panel') -->
@stop

