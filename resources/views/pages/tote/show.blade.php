@extends('pages.panelList')

@section('head')
    <!-- section('head') of pages/pallet/show.blade.php  -->

    @include('fields.cedIcons', ['model' => 'tote', 'elemType' => 'script'])

    <!-- stop of pages/pallet/show.blade.php, section('head') -->
@stop

@section('title')
    <!-- section('title') of pages/tote/show.blade.php  -->

    @lang('labels.titles.Tote')

    <!-- stop of pages/tote/show.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/tote/show.blade.php  -->

    @for($i = count($levels) -1; $i >= 0; $i--)
        @if($i === count($levels) -1)
            @lang($levels[$i]->name): {{ $levels[$i]->title }}
        @else
            , in @lang($levels[$i]->name): {!! link_to_route($levels[$i]->route, $levels[$i]->title, ['id' => $levels[$i]->id]) !!}
        @endif
    @endfor

    @include('fields.cedIcons', ['model' => 'tote', 'elemType' => 'div', 'id' => $tote->objectID])

    <!-- stop of pages/tote/show.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/tote/show.blade.php  -->

    {{--
    * desc Generic_Container;
    +-----------+-------------+------+-----+---------+-------+
    | Field     | Type        | Null | Key | Default | Extra |
    +-----------+-------------+------+-----+---------+-------+
    | objectID  | bigint(20)  | NO   | PRI | NULL    |       |
    | Carton_ID | varchar(85) | YES  | MUL | NULL    |       | contains a LPN (example '52 0015 9955'), or => Generic_Container, Tote or Pick
    | Status    | varchar(85) | YES  |     | OPEN    |       | values in ('OPEN', 'LOADED')
    +-----------+-------------+------+-----+---------+-------+
    --}}

    @if(Entrust::hasRole(['support']))
        @include('fields.textList', ['fieldName' => 'objectID' , 'fieldValue' => $tote->objectID  ])
    @endif
    @include('fields.textList', ['fieldName' => 'Carton_ID', 'fieldValue' => $tote->Carton_ID ])
    @include('fields.textList', ['fieldName' => 'Status'   , 'fieldValue' => Lang::get('lists.tote.status.'.$tote->Status) ])

    <!-- stop of pages/tote/show.blade.php, section('panel') -->
@stop

@section('list')
    <!-- section('list') of pages/tote/show.blade.php  -->

    {{-- var_dump($inventories) --}}
    @if(isset($inventories) && count($inventories))
        <h3>{!! Lang::get('labels.titles.Inventory_in') !!} {{ $tote->Carton_ID }}</h3>

        <!-- reuse pages.inventory.list -->
        @include('pages.inventory.list')
    @endif

    <!-- stop of pages/tote/show.blade.php, section('list') -->
@stop

