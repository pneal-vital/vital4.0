@extends('pages.panelList')

@section('title')
    <!-- section('title') of pages/inventory/show.blade.php  -->

    @lang('labels.titles.Inventory_for') {{ $inventory->Item }}

    <!-- stop of pages/inventory/show.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/inventory/show.blade.php  -->

    @for($i = count($levels) -1; $i >= 0; $i--)
        @if($i === count($levels) -1)
            @lang($levels[$i]->name): {{ $levels[$i]->title }}
        @else
            , in @lang($levels[$i]->name): {!! link_to_route($levels[$i]->route, $levels[$i]->title, ['id' => $levels[$i]->id]) !!}
        @endif
    @endfor

    <!-- stop of pages/inventory/show.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/inventory/show.blade.php  -->

    {{--
    * Table Structure
    * desc Inventory;
    +------------+-------------+------+-----+---------+-------+
    | Field      | Type        | Null | Key | Default | Extra |
    +------------+-------------+------+-----+---------+-------+
    | objectID   | bigint(20)  | NO   | PRI | NULL    |       |
    | Item       | varchar(85) | YES  | MUL | NULL    |       | => Item.objectID
    | Quantity   | varchar(85) | YES  |     | NULL    |       |
    | Created    | varchar(85) | YES  |     | NULL    |       |
    | Status     | varchar(85) | YES  | MUL | NULL    |       |
    | Order_Line | varchar(85) | YES  | MUL | NULL    |       | => Inbound_Order | Outbound_Order.objectID
    | UOM        | varchar(85) | YES  |     |         |       | => UOM.objectID
    +------------+-------------+------+-----+---------+-------+
    7 rows in set (0.01 sec)
    --}}

    @include('fields.textList', ['fieldName' => 'objectID'  , 'fieldValue' => $inventory->objectID   ])
    @include('fields.textList', ['fieldName' => 'Item'      , 'fieldValue' => $inventory->Item       ])
    @include('fields.textList', ['fieldName' => 'Quantity'  , 'fieldValue' => $inventory->Quantity   ])
    @include('fields.textList', ['fieldName' => 'Created'   , 'fieldValue' => $inventory->Created    ])
    @include('fields.textList', ['fieldName' => 'Status'    , 'fieldValue' => Lang::get('lists.inventory.status.'.$inventory->Status) ])
    @include('fields.textList', ['fieldName' => 'Order_Line', 'fieldValue' => $inventory->Order_Line ])
    @include('fields.textList', ['fieldName' => 'UOM'       , 'fieldValue' => $uoms[$inventory->UOM] ])

    <!-- stop of pages/inventory/show.blade.php, section('form') -->
@stop
