@extends('pages.panelList')

@section('title')
    <!-- section('title') of pages/purchaseOrder/index.blade.php  -->

    @lang('labels.titles.PurchaseOrders')

    <!-- stop of pages/purchaseOrder/index.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/purchaseOrder/index.blade.php  -->

    @lang('labels.titles.PurchaseOrder_Filter')

    <!-- stop of pages/purchaseOrder/index.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/purchaseOrder/index.blade.php  -->

    <!-- TODO learning, review FormBuilder.php and HtmlBuilder.php -->

    {{-- Filter fields --}}
    {!! Form::model($purchaseOrder, ['class' => 'form-horizontal', 'method' => 'patch', 'action' => 'vital40\PurchaseOrderController@filter']) !!}

        @include('pages.purchaseOrder.filter', ['labelType' => 'filter', 'submitButtonName' => 'PurchaseOrder_Filter'])

    {!! Form::close() !!}

    <!-- stop of pages/purchaseOrder/index.blade.php, section('form') -->
@stop

@section('list')
    <!-- section('list') of pages/purchaseOrder/index.blade.php  -->

    {{-- Filtered list --}}
    @include('pages.purchaseOrder.list')

    <!-- stop of pages/purchaseOrder/index.blade.php, section('list') -->
@stop
