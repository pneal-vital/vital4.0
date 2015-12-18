@extends('pages.panelList')

@section('title')
    <!-- section('title') of pages/inboundOrder/index.blade.php  -->

    @lang('labels.titles.InboundOrders')

    <!-- stop of pages/inboundOrder/index.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/inboundOrder/index.blade.php  -->

    @lang('labels.titles.InboundOrder_Filter')

    <!-- stop of pages/inboundOrder/index.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/inboundOrder/index.blade.php  -->

    {{-- Filter fields --}}
    {!! Form::model($inboundOrder, ['class' => 'form-horizontal', 'method' => 'patch', 'action' => 'vital3\InboundOrderController@filter']) !!}

        @include('pages.inboundOrder.filter', ['labelType' => 'filter', 'submitButtonName' => 'InboundOrder_Filter'])

    {!! Form::close() !!}

    <!-- stop of pages/inboundOrder/index.blade.php, section('form') -->
@stop

@section('list')
    <!-- section('list') of pages/inboundOrder/index.blade.php  -->

    {{-- Filtered list --}}
    @include('pages.inboundOrder.list')

    <!-- stop of pages/inboundOrder/index.blade.php, section('list') -->
@stop

