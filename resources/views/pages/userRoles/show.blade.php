@extends('pages.panel')

@section('panel')
    <!-- section('panel') of pages/userRoles/show.blade.php  -->

    <div class="panel-heading">@lang('labels.titles.UserRoles_for') {{ $userRoles->name }}</div>
    <div class="panel-body">

        <div class="form-group col-md-12 text-center">
            @lang('internal.userRoles.allows', ['user_id' => $userRoles->user_id])
        </div>

        @include('errors.list')

        {{--
            * Table Structure
            * desc role_user;
            +---------+------------------+------+-----+---------+-------+
            | Field   | Type             | Null | Key | Default | Extra |
            +---------+------------------+------+-----+---------+-------+
            | user_id | int(10) unsigned | NO   | PRI | NULL    |       |
            | role_id | int(10) unsigned | NO   | PRI | NULL    |       |
            +---------+------------------+------+-----+---------+-------+
            2 rows in set (0.00 sec)
        --}}

        @include('fields.textList', ['fieldName' => 'user_id', 'fieldValue' => $userRoles->user_id ])
        @include('fields.textList', ['fieldName' => 'role_id', 'fieldValue' => $userRoles->role_id ])

    </div>

    <!-- stop of pages/userRoles/show.blade.php, section('panel') -->
@stop

