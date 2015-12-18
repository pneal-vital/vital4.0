@extends('pages.panel')

@section('panel')
    <!-- section('panel') of pages/user/show.blade.php  -->

    <div class="panel-heading">@lang('labels.titles.User_for') {{ $user->name }}</div>
    <div class="panel-body">

        @include('errors.list')

        {{--
            * Table Structure
            * desc users;
            +----------------+------------------+------+-----+---------------------+----------------+
            | Field          | Type             | Null | Key | Default             | Extra          |
            +----------------+------------------+------+-----+---------------------+----------------+
            | id             | int(10) unsigned | NO   | PRI | NULL                | auto_increment |
            | name           | varchar(255)     | NO   |     | NULL                |                |
            | email          | varchar(255)     | NO   | UNI | NULL                |                |
            | password       | varchar(60)      | NO   |     | NULL                |                |
            | remember_token | varchar(100)     | YES  |     | NULL                |                |
            | created_at     | timestamp        | NO   |     | 0000-00-00 00:00:00 |                |
            | updated_at     | timestamp        | NO   |     | 0000-00-00 00:00:00 |                |
            +----------------+------------------+------+-----+---------------------+----------------+
            7 rows in set (0.00 sec)
        --}}

        @if(Entrust::hasRole(['support']))
            @include('fields.textList', ['fieldName' => 'id'    , 'fieldValue' => $user->id         ])
        @endif
        @include('fields.textList', ['fieldName' => 'name'      , 'fieldValue' => $user->name       ])
        @include('fields.textList', ['fieldName' => 'email'     , 'fieldValue' => $user->email      ])
        @include('fields.textList', ['fieldName' => 'created_at', 'fieldValue' => $user->created_at ])
        @if($user->updated_at > '0000-00-00')
            @include('fields.textList', ['fieldName' => 'updated_at', 'fieldValue' => $user->updated_at ])
        @else
            @include('fields.textList', ['fieldName' => 'updated_at', 'fieldValue' => '' ])
        @endif

    </div>

    <!-- stop of pages/user/show.blade.php, section('panel') -->
@stop

