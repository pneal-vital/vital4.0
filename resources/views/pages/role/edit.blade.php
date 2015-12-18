@extends('pages.panel')

@section('panel')
    <!-- section('panel') of pages/role/edit.blade.php  -->

    <div class="panel-heading">@lang('labels.titles.Edit_Role')</div>
    <div class="panel-body">

        @include('errors.list')

        {{-- Using form-model binding --}}
        {!! Form::model($role, ['class' => 'form-horizontal', 'method' => 'PATCH', 'action' => ['RoleController@update', $role->id]]) !!}

            @include('pages.role.form', ['submitButtonName' => 'Update_Role'])

        {!! Form::close() !!}

    </div>

<!-- stop of pages/role/edit.blade.php, section('panel') -->
@stop

