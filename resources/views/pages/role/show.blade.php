@extends('pages.panel')

@section('panel')
    <!-- section('panel') of pages/role/show.blade.php  -->

    <div class="panel-heading">@lang('labels.titles.Role_for') {{ $role->name }}</div>
    <div class="panel-body">

        <div class="form-group col-md-12 text-center">
            @lang('internal.role.allows', ['display_name' => $role->display_name, 'description' => $role->description])
        </div>

        @include('errors.list')

        {{--
            * Table Structure
            * desc roles;
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
            @include('fields.textList', ['fieldName' => 'id'      , 'fieldValue' => $role->id           ])
        @endif
        @include('fields.textList', ['fieldName' => 'name'        , 'fieldValue' => $role->name         ])
        @include('fields.textList', ['fieldName' => 'display_name', 'fieldValue' => $role->display_name ])
        @include('fields.textList', ['fieldName' => 'description' , 'fieldValue' => $role->description  ])
        @include('fields.textList', ['fieldName' => 'created_at'  , 'fieldValue' => $role->created_at   ])
        @include('fields.textList', ['fieldName' => 'updated_at'  , 'fieldValue' => $role->updated_at   ])

    </div>

    <!-- stop of pages/role/show.blade.php, section('panel') -->
@stop

