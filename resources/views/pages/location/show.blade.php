@extends('pages.panelList')

@section('title')
    <!-- section('title') of pages/location/show.blade.php  -->

    @lang('labels.titles.Location')

    <!-- stop of pages/location/show.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/location/show.blade.php  -->

    @for($i = count($levels) -1; $i >= 0; $i--)
        @if($i === count($levels) -1)
            @lang($levels[$i]->name): {{ $levels[$i]->title }}
        @else
            , in @lang($levels[$i]->name): {!! link_to_route($levels[$i]->route, $levels[$i]->title, ['id' => $levels[$i]->id]) !!}
        @endif
    @endfor

    <!-- stop of pages/location/show.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/location/show.blade.php  -->
    
    {{--
    * Location;
    +---------------+-------------+------+-----+---------+-------+
    | Field         | Type        | Null | Key | Default | Extra |
    +---------------+-------------+------+-----+---------+-------+
    | objectID      | bigint(20)  | NO   | PRI | NULL    |       |
    | Location_Name | varchar(85) | YES  | MUL | NULL    |       |
    | Capacity      | varchar(85) | YES  |     | NULL    |       | in ('', 1, 6, 999, 9999), set to 1
    | x             | varchar(85) | YES  |     | NULL    |       |
    | y             | varchar(85) | YES  |     | NULL    |       |
    | z             | varchar(85) | YES  |     | NULL    |       |
    | LocType       | varchar(85) | YES  | MUL | NULL    |       | may be '', 'ACTIVITY', 'RESERVE', 'WORK', or 'PICK' + pick Sequence number
    | Comingle      | varchar(85) | YES  |     | NULL    |       | in ('N', 'P')
    +---------------+-------------+------+-----+---------+-------+
    --}}

    @include('fields.textList', ['fieldName' => 'Location_Name', 'fieldValue' => $location->Location_Name ])
    @if(Entrust::hasRole(['support']))
        @include('fields.textList', ['fieldName' => 'Capacity'     , 'fieldValue' => $location->Capacity      ])
        @include('fields.textList', ['fieldName' => 'x'            , 'fieldValue' => $location->x             ])
        @include('fields.textList', ['fieldName' => 'y'            , 'fieldValue' => $location->y             ])
        @include('fields.textList', ['fieldName' => 'z'            , 'fieldValue' => $location->z             ])
    @endif
    @include('fields.textList', ['fieldName' => 'LocType'      , 'fieldValue' => $location->LocType       ])
    @include('fields.textList', ['fieldName' => 'Comingle'     , 'fieldValue' => $location->Comingle      ])

    <!-- stop of pages/location/show.blade.php, section('form') -->
@stop

@section('list')
    <!-- section('list') of pages/location/show.blade.php  -->

    {{-- var_dump($pallets) --}}
    @if(isset($pallets) && count($pallets))
        <h3>{!! Lang::get('labels.titles.Pallets_in') !!} {{ $location->Location_Name }}</h3>

        <!-- reuse pages.pallet.list -->
        @include('pages.pallet.list')
    @endif

    <!-- stop of pages/location/show.blade.php, section('list') -->
@stop
