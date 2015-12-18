@extends('pages.panelList')

@section('title')
    <!-- section('title') of pages/receiveLocation/index.blade.php  -->

    @lang('labels.titles.Receive_Location') {{ $location->Location_Name }}

    <!-- stop of pages/receiveLocation/index.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/receiveLocation/show.blade.php  -->

    @lang('labels.titles.Receive_Location')

    <!-- stop of pages/receiveLocation/index.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/receiveLocation/show.blade.php  -->

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
    @include('fields.textList', ['fieldName' => 'Capacity'     , 'fieldValue' => $location->Capacity      ])
    @include('fields.textList', ['fieldName' => 'x'            , 'fieldValue' => $location->x             ])
    @include('fields.textList', ['fieldName' => 'y'            , 'fieldValue' => $location->y             ])
    @include('fields.textList', ['fieldName' => 'z'            , 'fieldValue' => $location->z             ])
    @include('fields.textList', ['fieldName' => 'LocType'      , 'fieldValue' => $location->LocType       ])
    @include('fields.textList', ['fieldName' => 'Comingle'     , 'fieldValue' => $location->Comingle      ])

    <!-- stop of pages/receiveLocation/show.blade.php, section('form') -->
@stop

