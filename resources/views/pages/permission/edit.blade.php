@extends('pages.panel')

@section('panel')
    <!-- section('panel') of pages/permission/edit.blade.php  -->

    <div class="panel-heading">@lang('labels.titles.Edit_Permission')</div>
    <div class="panel-body">

        @include('errors.list')

        {{-- Using form-model binding --}}
        {!! Form::model($permission, ['class' => 'form-horizontal', 'method' => 'PATCH', 'action' => ['PermissionController@update', $permission->id]]) !!}

            @include('pages.permission.form', ['submitButtonName' => 'Update_Permission'])

        {!! Form::close() !!}

    </div>

<!-- stop of pages/permission/edit.blade.php, section('panel') -->
@stop

