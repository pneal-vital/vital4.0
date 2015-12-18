@extends('pages.panelList')

@section('title')
    <!-- section('title') of pages/permission/index.blade.php  -->

    @lang('labels.titles.Permissions')

    <!-- stop of pages/permission/index.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/permission/index.blade.php  -->

    @lang('labels.titles.Permission_Filter')

    <!-- stop of pages/permission/index.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/permission/index.blade.php  -->

    {{-- Filter fields --}}
    {!! Form::model($permission, ['class' => 'form-horizontal', 'method' => 'patch', 'action' => 'PermissionController@filter']) !!}

        @include('pages.permission.filter', ['labelType' => 'filter', 'submitButtonName' => 'Permission_Filter'])

    {!! Form::close() !!}

    <!-- stop of pages/permission/index.blade.php, section('form') -->
@stop

@section('list')
    <!-- section('list') of pages/permission/index.blade.php  -->

    {{-- Filtered list --}}
    @include('pages.permission.list')

    <!-- stop of pages/permission/index.blade.php, section('list') -->
@stop
