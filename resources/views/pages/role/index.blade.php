@extends('pages.panelList')

@section('title')
    <!-- section('title') of pages/role/index.blade.php  -->

    @lang('labels.titles.Roles')

    <!-- stop of pages/role/index.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/role/index.blade.php  -->

    @lang('labels.titles.Role_Filter')

    <!-- stop of pages/role/index.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/role/index.blade.php  -->

    {{-- Filter fields --}}
    {!! Form::model($role, ['class' => 'form-horizontal', 'method' => 'patch', 'action' => 'RoleController@filter']) !!}

        @include('pages.role.filter', ['labelType' => 'filter', 'submitButtonName' => 'Role_Filter'])

    {!! Form::close() !!}

    <!-- stop of pages/role/index.blade.php, section('form') -->
@stop

@section('list')
    <!-- section('list') of pages/role/index.blade.php  -->

    {{-- Filtered list --}}
    @include('pages.role.list')

    <!-- stop of pages/role/index.blade.php, section('list') -->
@stop
