@extends('pages.panel')

@section('panel')
    <!-- section('panel') of pages/permission/create.blade.php  -->

    <div class="panel-heading">@lang('labels.titles.Create_Permission')</div>
    <div class="panel-body">

        @include('errors.list')

        {!! Form::open(['class' => 'form-horizontal', 'url' => 'permission']) !!}

            @include('pages.permission.form', ['submitButtonName' => 'Add_Permission'])

        {!! Form::close() !!}

    </div>

<!-- stop of pages/permission/create.blade.php, section('panel') -->
@stop

