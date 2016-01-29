@extends('pages.panel')

@section('panel')
    <!-- section('panel') of pages/rolePermissions/create.blade.php  -->

    <div class="panel-heading">@lang('labels.titles.Create_RolePermissions')</div>
    <div class="panel-body">

        @include('errors.list')

        {!! Form::open(['class' => 'form-horizontal', 'url' => 'rolePermissions']) !!}

            @include('pages.rolePermissions.form', ['submitButtonName' => 'Add_RolePermissions'])

        {!! Form::close() !!}

    </div>

<!-- stop of pages/rolePermissions/create.blade.php, section('panel') -->
@stop

