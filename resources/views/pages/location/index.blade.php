@extends('pages.panelList')

@section('title')
    <!-- section('title') of pages/location/index.blade.php  -->

    @lang('labels.titles.Location')

    <!-- stop of pages/location/index.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/location/index.blade.php  -->

    @lang('labels.titles.Location_Filter')

    <!-- stop of pages/location/index.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/location/index.blade.php  -->

    {{-- Filter fields --}}
    {!! Form::model($location, ['class' => 'form-horizontal', 'method' => 'patch', 'action' => 'LocationController@filter']) !!}

        @include('pages.location.filter', ['labelType' => 'filter', 'submitButtonName' => 'Location_Filter'])

    {!! Form::close() !!}

    <!-- stop of pages/location/index.blade.php, section('form') -->
@stop

@section('list')
    <!-- section('list') of pages/location/index.blade.php  -->

    {{-- Filtered list --}}
    @include('pages.location.list')

    <!-- stop of pages/location/index.blade.php, section('list') -->
@stop
