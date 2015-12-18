@extends('pages.panelList')

@section('title')
    <!-- section('title') of pages/receivePO/index.blade.php  -->

    @lang('labels.titles.Receive_PO') {{ $purchaseOrder->Order_Number }}

    <!-- stop of pages/receivePO/index.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/receivePO/show.blade.php  -->

    @lang('labels.titles.Receive_PO')

    <!-- stop of pages/receivePO/index.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/receivePO/show.blade.php  -->

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

    @include('fields.textList', ['fieldName' => 'Purchase_Order' , 'fieldValue' => $purchaseOrder->Purchase_Order ])
    @if(Entrust::hasRole(['support']))
        @include('fields.textList', ['fieldName' => 'objectID'       , 'fieldValue' => $purchaseOrder->objectID       ])
        @include('fields.textList', ['fieldName' => 'Order_Number'   , 'fieldValue' => $purchaseOrder->Order_Number   ])
        @include('fields.textList', ['fieldName' => 'Client'         , 'fieldValue' => $purchaseOrder->Client         ])
        @include('fields.textList', ['fieldName' => 'Invoice_Number' , 'fieldValue' => $purchaseOrder->Invoice_Number ])
    @endif
    @include('fields.textList', ['fieldName' => 'Status'         , 'fieldValue' => Lang::get('lists.purchaseOrder.status.'.$purchaseOrder->Status) ])
    @if(Entrust::hasRole(['support']))
        @include('fields.textList', ['fieldName' => 'Created'        , 'fieldValue' => $purchaseOrder->Created        ])
    @endif
    @include('fields.textList', ['fieldName' => 'Expected'       , 'fieldValue' => $purchaseOrder->Expected       ])

    <!-- stop of pages/receivePO/show.blade.php, section('form') -->
@stop

@section('list')
    <!-- section('list') of pages/receivePO/show.blade.php  -->

    @if(isset($receiveArticles) && count($receiveArticles))
        <h3>{!! Lang::get('labels.titles.Receive_Articles') !!}</h3>

        <!-- reuse pages.receiveArticle.list -->
        @include('pages.receiveArticle.list')
    @endif

    <!-- stop of pages/receivePO/show.blade.php, section('list') -->
@stop
