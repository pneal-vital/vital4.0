@extends('pages.panelList')

@section('title')
    <!-- section('title') of pages/invSummary/index.blade.php  -->

    @lang('labels.titles.InventorySummary')

    <!-- stop of pages/invSummary/index.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/invSummary/index.blade.php  -->

    @lang('labels.titles.InventorySummary_Filter')

    <!-- stop of pages/invSummary/index.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/invSummary/index.blade.php  -->

    {{-- Filter fields --}}
    {!! Form::model($invSummary, ['class' => 'form-horizontal', 'method' => 'patch', 'action' => 'InventorySummaryController@filter']) !!}

        @include('pages.invSummary.filter', ['labelType' => 'filter', 'submitButtonName' => 'InventorySummary_Filter'])

    {!! Form::close() !!}

    <!-- stop of pages/invSummary/index.blade.php, section('form') -->
@stop

@section('list')
    <!-- section('list') of pages/invSummary/index.blade.php  -->

    {{-- Filtered list --}}
    @include('pages.invSummary.list')

    <!-- stop of pages/invSummary/index.blade.php, section('list') -->
@stop

