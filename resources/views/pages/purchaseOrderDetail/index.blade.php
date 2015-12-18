@extends('pages.panelList')

@section('title')
    <!-- section('title') of pages/purchaseOrderDetail/index.blade.php  -->

    @lang('labels.titles.PurchaseOrderDetails')

    <!-- stop of pages/purchaseOrderDetail/index.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/purchaseOrderDetail/index.blade.php  -->

    @lang('labels.titles.PurchaseOrderDetail_Filter')

    <!-- stop of pages/purchaseOrderDetail/index.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/purchaseOrderDetail/index.blade.php  -->

    {{-- Filter fields --}}
    {!! Form::model($purchaseOrderDetail, ['class' => 'form-horizontal', 'method' => 'patch', 'action' => 'vital40\PurchaseOrderDetailController@filter']) !!}

        @include('pages.purchaseOrderDetail.filter', ['labelType' => 'filter', 'submitButtonName' => 'PurchaseOrderDetail_Filter'])

    {!! Form::close() !!}

    <!-- stop of pages/purchaseOrderDetail/index.blade.php, section('form') -->
@stop

@section('list')
    <!-- section('list') of pages/purchaseOrderDetail/index.blade.php  -->

    {{-- Filtered list --}}
    @include('pages.purchaseOrderDetail.list')

<!-- stop of pages/purchaseOrderDetail/index.blade.php, section('list') -->
@stop

