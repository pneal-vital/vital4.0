@extends('pages.panel')

@section('panel')
    <!-- section('panel') of pages/permission/show.blade.php  -->

    <div class="panel-heading">@lang('labels.titles.Permission_for') {{ $permission->name }}</div>
    <div class="panel-body">

        <div class="form-group col-md-12 text-center">
            @lang('internal.permission.allows', ['display_name' => $permission->display_name, 'description' => $permission->description])
        </div>

        @include('errors.list')

        {{--
            * Table Structure
            * desc permissions;
        +--------------+------------------+------+-----+---------------------+----------------+
        | Field        | Type             | Null | Key | Default             | Extra          |
        +--------------+------------------+------+-----+---------------------+----------------+
        | id           | int(10) unsigned | NO   | PRI | NULL                | auto_increment |
        | name         | varchar(255)     | NO   | UNI | NULL                |                |
        | display_name | varchar(255)     | YES  |     | NULL                |                |
        | description  | varchar(255)     | YES  |     | NULL                |                |
        | created_at   | timestamp        | NO   |     | 0000-00-00 00:00:00 |                |
        | updated_at   | timestamp        | NO   |     | 0000-00-00 00:00:00 |                |
        +--------------+------------------+------+-----+---------------------+----------------+
        6 rows in set (0.01 sec)
        --}}

        @if(Entrust::hasRole(['support']))
            @include('fields.textList', ['fieldName' => 'id'      , 'fieldValue' => $permission->id           ])
        @endif
        @include('fields.textList', ['fieldName' => 'name'        , 'fieldValue' => $permission->name         ])
        @include('fields.textList', ['fieldName' => 'display_name', 'fieldValue' => $permission->display_name ])
        @include('fields.textList', ['fieldName' => 'description' , 'fieldValue' => $permission->description  ])
        @include('fields.textList', ['fieldName' => 'created_at'  , 'fieldValue' => $permission->created_at   ])
        @include('fields.textList', ['fieldName' => 'updated_at'  , 'fieldValue' => $permission->updated_at   ])

    </div>

    <!-- stop of pages/permission/show.blade.php, section('panel') -->
@stop

