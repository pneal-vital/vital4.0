@extends('pages.panelList')

@section('title')
    <!-- section('title') of pages/pallet/show.blade.php  -->

    @lang('labels.titles.Pallet')

    <!-- stop of pages/pallet/show.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/pallet/show.blade.php  -->

    @for($i = count($levels) -1; $i >= 0; $i--)
        @if($i === count($levels) -1)
            @lang($levels[$i]->name): {{ $levels[$i]->title }}
        @else
            , in @lang($levels[$i]->name): {!! link_to_route($levels[$i]->route, $levels[$i]->title, ['id' => $levels[$i]->id]) !!}
        @endif
    @endfor

    <!-- stop of pages/pallet/show.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/pallet/show.blade.php  -->
    
    {{--
    * desc Pallet;
    +-----------+-------------+------+-----+---------+-------+
    | Field     | Type        | Null | Key | Default | Extra |
    +-----------+-------------+------+-----+---------+-------+
    | objectID  | bigint(20)  | NO   | PRI | 0       |       |
    | Pallet_ID | varchar(85) | NO   | MUL |         |       | contains names like INBOUND, or => Generic_Container, Inventory, Item, Label_Printer, Outbound_Order_Detail, Pallet, Pick, Pick_Detail, Shipment
    | x         | varchar(85) | NO   |     |         |       |
    | y         | varchar(85) | NO   |     |         |       |
    | z         | varchar(85) | NO   |     |         |       |
    | Status    | varchar(85) | NO   |     |         |       | in ('LOCK', 'OPEN', 'LOADED', 'SHIPPED')
    +-----------+-------------+------+-----+---------+-------+
    --}}

    @include('fields.textList', ['fieldName' => 'Pallet_ID', 'fieldValue' => $pallet->Pallet_ID ])
    @if(Entrust::hasRole(['support']))
        @include('fields.textList', ['fieldName' => 'x'        , 'fieldValue' => $pallet->x         ])
        @include('fields.textList', ['fieldName' => 'y'        , 'fieldValue' => $pallet->y         ])
        @include('fields.textList', ['fieldName' => 'z'        , 'fieldValue' => $pallet->z         ])
    @endif
    @include('fields.textList', ['fieldName' => 'Status'   , 'fieldValue' => Lang::get('lists.pallet.status.'.$pallet->Status) ])

    <!-- stop of pages/pallet/show.blade.php, section('form') -->
@stop

@section('list')
    <!-- section('list') of pages/pallet/show.blade.php  -->

    {{-- var_dump($totes) --}}
    @if(isset($totes) && count($totes))
        <h3>{!! Lang::get('labels.titles.Totes_on') !!} {{ $pallet->Pallet_ID }}</h3>

        <!-- reuse pages.tote.list -->
        @include('pages.tote.list')
    @endif

    <!-- stop of pages/pallet/show.blade.php, section('list') -->
@stop
