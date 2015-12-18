@extends('pages.panelList')

@section('title')
    <!-- section('title') of pages/poReconciliation/index.blade.php  -->

    @lang('labels.titles.PO_Reconciliations')

    <!-- stop of pages/poReconciliation/index.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/poReconciliation/index.blade.php  -->

    @lang('labels.titles.PO_Reconciliation_Filter')

    <!-- stop of pages/poReconciliation/index.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/poReconciliation/index.blade.php  -->

    {{-- PurchaseOrder filter fields --}}
    {!! Form::model($purchaseOrder, ['class' => 'form-horizontal', 'method' => 'post', 'action' => 'Receive\POReconciliationController@filter']) !!}

        @include('pages.poReconciliation.filter', ['labelType' => 'filter', 'submitButtonName' => 'PO_Reconciliation_Filter'])

    {!! Form::close() !!}

    <!-- stop of pages/poReconciliation/index.blade.php, section('form') -->
@stop

@section('list')
    <!-- section('list') of pages/poReconciliation/index.blade.php  -->

    {{--  filtered list --}}
    @include('pages.purchaseOrder.list', ['route' => 'poReconciliation.show'])

    <!-- stop of pages/poReconciliation/index.blade.php, section('list') -->
@stop
