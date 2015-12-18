@extends('pages.panel')

@section('panel')
    <!-- section('panel') of pages/role/create.blade.php  -->

    <div class="panel-heading">@lang('labels.titles.Create_Role')</div>
    <div class="panel-body">

        @include('errors.list')

        {!! Form::open(['class' => 'form-horizontal', 'url' => 'role']) !!}

            @include('pages.role.form', ['submitButtonName' => 'Add_Role'])

        {!! Form::close() !!}

    </div>

<!-- stop of pages/role/create.blade.php, section('panel') -->
@stop

