@extends('pages.panelList')

@section('title')
    <!-- section('title') of pages/performanceTally/index.blade.php  -->

    @lang('labels.titles.PerformanceTally')

    <!-- stop of pages/performanceTally/index.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/performanceTally/index.blade.php  -->

    @lang('labels.titles.PerformanceTally_Filter')

    <!-- stop of pages/performanceTally/index.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/performanceTally/index.blade.php  -->

    {{-- Filter fields --}}
    {!! Form::model($performanceTally, ['class' => 'form-horizontal', 'method' => 'patch', 'action' => 'PerformanceTallyController@filter']) !!}

        @include('pages.performanceTally.filter', ['labelType' => 'filter', 'submitButtonName' => 'PerformanceTally_Filter'])

    {!! Form::close() !!}

    <!-- stop of pages/performanceTally/index.blade.php, section('form') -->
@stop

@section('list')
    <!-- section('list') of pages/performanceTally/index.blade.php  -->

    {{-- Filtered list --}}
    @include('pages.performanceTally.list')

    <!-- stop of pages/performanceTally/index.blade.php, section('list') -->
@stop
