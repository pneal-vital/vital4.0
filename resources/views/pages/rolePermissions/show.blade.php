@extends('pages.panel')

@section('panel')
    <!-- section('panel') of pages/rolePermissions/show.blade.php  -->

    <div class="panel-heading">@lang('labels.titles.RolePermissions_for') {{ $rolePermissions->name }}</div>
    <div class="panel-body">

        <div class="form-group col-md-12 text-center">
            @lang('internal.rolePermissions.allows', ['role_id' => $rolePermissions->role_id])
        </div>

        @include('errors.list')

        {{--
            * Table Structure
            * desc permission_role;
            +---------------+------------------+------+-----+---------+-------+
            | Field         | Type             | Null | Key | Default | Extra |
            +---------------+------------------+------+-----+---------+-------+
            | permission_id | int(10) unsigned | NO   | PRI | NULL    |       |
            | role_id       | int(10) unsigned | NO   | PRI | NULL    |       |
            +---------------+------------------+------+-----+---------+-------+
            2 rows in set (0.02 sec)
        --}}

        @include('fields.textList', ['fieldName' => 'role_id'      , 'fieldValue' => $rolePermissions->role_id       ])
        @include('fields.textList', ['fieldName' => 'permission_id', 'fieldValue' => $rolePermissions->permission_id ])

    </div>

    <!-- stop of pages/rolePermissions/show.blade.php, section('panel') -->
@stop

