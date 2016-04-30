@extends('pages.panelList')

@section('head')
    <!-- section('head') of pages/pallet/show.blade.php  -->

    @include('fields.cedIcons', ['model' => 'inventory', 'elemType' => 'script'])

    <!-- stop of pages/pallet/show.blade.php, section('head') -->
@stop

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

    @include('fields.cedIcons', ['model' => 'inventory', 'elemType' => 'div', 'id' => $inventory->objectID])

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

    @if(Entrust::hasRole(['support']))
        @include('fields.textList', ['fieldName' => 'objectID'  , 'fieldValue' => $inventory->objectID   ])
    @endif
    @if($inventory->Item_type == 'UPC')
        @include('fields.textList', ['fieldName' => 'UPC'    , 'fieldValue' => $inventory->Item_typeID.', '.$inventory->Item_description, 'urlID' => $inventory->Item, 'urlName' => 'upc.show'     ])
    @else
        @include('fields.textList', ['fieldName' => 'Article', 'fieldValue' => $inventory->Item_typeID.', '.$inventory->Item_description, 'urlID' => $inventory->Item, 'urlName' => 'article.show' ])
    @endif
    @include('fields.textList', ['fieldName' => 'Quantity'  , 'fieldValue' => $inventory->Quantity   ])
    @include('fields.textList', ['fieldName' => 'Created'   , 'fieldValue' => $inventory->Created    ])
    @include('fields.textList', ['fieldName' => 'Status'    , 'fieldValue' => Lang::get('lists.inventory.status.'.$inventory->Status) ])
    @if(isset($inventory->Order_Line_type))
        @if($inventory->Order_Line_type == 'OutboundOrderDetail')
            @include('fields.textList', ['fieldName' => $inventory->Order_Line_type, 'fieldValue' => 'Line '.$inventory->Order_Line.' of '.$inventory->Order_Line_typeID ])
        @else
            @include('fields.textList', ['fieldName' => $inventory->Order_Line_type, 'fieldValue' => $inventory->Order_Line_typeID, 'urlID' => $inventory->Order_Line, 'urlName' => \Config::get("constants.routeName.$inventory->Order_Line_type.show") ])
        @endif
    @else
        @include('fields.textList', ['fieldName' => 'Order_Line', 'fieldValue' => $inventory->Order_Line ])
    @endif
    @include('fields.textList', ['fieldName' => 'UOM'       , 'fieldValue' => $uoms[$inventory->UOM] ])

    <!-- stop of pages/inventory/show.blade.php, section('form') -->
@stop
