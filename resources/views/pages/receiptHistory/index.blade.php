@extends('pages.panelList')

@section('title')
    <!-- section('title') of pages/receiptHistory/index.blade.php  -->

    @lang('labels.titles.ReceiptHistories')

    <!-- stop of pages/receiptHistory/index.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/receiptHistory/index.blade.php  -->

    @lang('labels.titles.ReceiptHistory_Filter')

    <!-- stop of pages/receiptHistory/index.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/receiptHistory/index.blade.php  -->

    {{-- Filter fields --}}
    {!! Form::model($receiptHistory, ['class' => 'form-horizontal', 'method' => 'patch', 'action' => 'ReceiptHistoryController@filter']) !!}

        @include('pages.receiptHistory.filter', ['labelType' => 'filter', 'submitButtonName' => 'ReceiptHistory_Filter'])

    {!! Form::close() !!}

    <!-- stop of pages/receiptHistory/index.blade.php, section('form') -->
@stop

@section('list')
    <!-- section('list') of pages/receiptHistory/index.blade.php  -->

    {{-- Filtered list --}}
    @include('pages.receiptHistory.list')

    <!-- stop of pages/receiptHistory/index.blade.php, section('list') -->
@stop
