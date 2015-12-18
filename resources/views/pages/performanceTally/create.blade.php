@extends('pages.panel')

@section('panel')
    <!-- section('panel') of pages/performanceTally/create.blade.php  -->

    <div class="panel-heading">@lang('labels.titles.Create_PerformanceTally')</div>
    <div class="panel-body">

        @include('errors.list')

        {!! Form::open(['class' => 'form-horizontal', 'url' => 'performanceTally']) !!}

            @include('pages.performanceTally.form', ['submitButtonName' => 'Add_PerformanceTally'])

        {!! Form::close() !!}

    </div>

<!-- stop of pages/performanceTally/create.blade.php, section('panel') -->
@stop

