@extends('pages.panelList')

@section('title')
    <!-- section('title') of pages/reworkReport/index.blade.php  -->

    @lang('labels.titles.Rework_Report')

    <!-- stop of pages/reworkReport/index.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/reworkReport/index.blade.php  -->

    @lang('labels.titles.Rework_Report_Filter')

    <!-- stop of pages/reworkReport/index.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/reworkReport/index.blade.php  -->

    {{-- Filter fields --}}
    {!! Form::model($reworkReport, ['class' => 'form-horizontal', 'method' => 'patch', 'action' => 'ReworkReportController@filter']) !!}

        @include('pages.reworkReport.filter', ['labelType' => 'filter', 'submitButtonName' => 'Rework_Report_Filter'])

    {!! Form::close() !!}

    <!-- stop of pages/reworkReport/index.blade.php, section('form') -->
@stop

@section('list')
    <!-- section('list') of pages/reworkReport/index.blade.php  -->

    {{-- Filtered list --}}
    @include('pages.reworkReport.list')

    <!-- stop of pages/reworkReport/index.blade.php, section('list') -->
@stop

@section('footer')
    <!-- section('footer') of pages/reworkReport/index.blade.php  -->

    @if(count($reworkReports))
        {{-- Export form --}}
        {!! Form::model($reworkReport, ['class' => 'form-horizontal', 'method' => 'patch', 'action' => 'ReworkReportController@export']) !!}

            @include('fields.ddList', ['fieldName' => 'ExportType', 'lists' => $exportTypes, 'fieldSize' => 'col-md-6'])

            @include('fields.button', ['submitButtonName' => 'Rework_Report_Export'])

        {!! Form::close() !!}
    @endif

    <!-- stop of pages/reworkReport/index.blade.php, section('footer') -->
@stop
