@extends('pages.panelList')

@section('head')
    <!-- section('head') of pages/warehouse/show.blade.php  -->

    @include('fields.cedIcons', ['model' => 'warehouse', 'elemType' => 'script'])

    <!-- stop of pages/warehouse/show.blade.php, section('head') -->
@stop

@section('title')
    <!-- section('title') of pages/warehouse/show.blade.php  -->

    @lang('labels.titles.Warehouse')

    <!-- stop of pages/warehouse/show.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/warehouse/show.blade.php  -->

    @for($i = count($levels) -1; $i >= 0; $i--)
        @if($i === count($levels) -1)
            @lang($levels[$i]->name): {{ $levels[$i]->title }}
        @else
            , in @lang($levels[$i]->name): {!! link_to_route($levels[$i]->route, $levels[$i]->title, ['id' => $levels[$i]->id]) !!}
        @endif
    @endfor

    @include('fields.cedIcons', ['model' => 'warehouse', 'elemType' => 'div', 'id' => $warehouse->objectID])

    <!-- stop of pages/warehouse/show.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/warehouse/show.blade.php  -->
    
    {{--
    * desc Warehouse;
    +----------------+-------------+------+-----+---------+-------+
    | Field          | Type        | Null | Key | Default | Extra |
    +----------------+-------------+------+-----+---------+-------+
    | objectID       | bigint(20)  | NO   | PRI | NULL    |       |
    | Warehouse_Code | varchar(85) | YES  |     | NULL    |       |
    | Warehouse_Name | varchar(85) | YES  |     | NULL    |       |
    | Address_1      | varchar(85) | YES  |     | NULL    |       |
    | Address_2      | varchar(85) | YES  |     | NULL    |       |
    | City           | varchar(85) | YES  |     | NULL    |       |
    | Province       | varchar(85) | YES  |     | NULL    |       |
    | Post_Code      | varchar(85) | YES  |     | NULL    |       |
    | Phone          | varchar(85) | YES  |     | NULL    |       |
    | Fax            | varchar(85) | YES  |     | NULL    |       |
    | Remote_Address | varchar(85) | YES  |     | NULL    |       |
    +----------------+-------------+------+-----+---------+-------+
    11 rows in set (0.02 sec)
    --}}

    @if(Entrust::hasRole(['support']))
        @include('fields.textList', ['fieldName' => 'objectID'  , 'fieldValue' => $warehouse->objectID  ])
    @endif
    @include('fields.textList', ['fieldName' => 'Warehouse_Code', 'fieldValue' => $warehouse->Warehouse_Code ])
    @include('fields.textList', ['fieldName' => 'Warehouse_Name', 'fieldValue' => $warehouse->Warehouse_Name ])
    @include('fields.textList', ['fieldName' => 'Address_1'     , 'fieldValue' => $warehouse->Address_1      ])
    @include('fields.textList', ['fieldName' => 'Address_2'     , 'fieldValue' => $warehouse->Address_1      ])
    @include('fields.textList', ['fieldName' => 'City'          , 'fieldValue' => $warehouse->City           ])
    @include('fields.textList', ['fieldName' => 'Province'      , 'fieldValue' => $warehouse->Province       ])
    @include('fields.textList', ['fieldName' => 'Post_Code'     , 'fieldValue' => $warehouse->Post_Code      ])
    @include('fields.textList', ['fieldName' => 'Phone'         , 'fieldValue' => $warehouse->Phone          ])
    @include('fields.textList', ['fieldName' => 'Fax'           , 'fieldValue' => $warehouse->Fax            ])
    @include('fields.textList', ['fieldName' => 'Remote_Address', 'fieldValue' => $warehouse->Remote_Address ])

    <!-- stop of pages/warehouse/show.blade.php, section('form') -->
@stop

@section('list')
    <!-- section('list') of pages/warehouse/show.blade.php  -->

    {{-- var_dump($locations) --}}
    @if(isset($locations) && count($locations))
        <h3>{!! Lang::get('labels.titles.Locations_in') !!} {{ $warehouse->Warehouse_Name }}</h3>

        <!-- reuse pages.location.list -->
        @include('pages.location.list')
    @endif

    <!-- stop of pages/warehouse/show.blade.php, section('list') -->
@stop
