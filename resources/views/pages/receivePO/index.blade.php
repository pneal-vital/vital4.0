@extends('pages.panelList')

@section('title')
    <!-- section('title') of pages/receivePO/index.blade.php  -->

    @lang('labels.titles.Receive_POs')

    <!-- stop of pages/receivePO/index.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/receivePO/index.blade.php  -->

    @lang('labels.titles.Receive_PO_Filter')

    <!-- stop of pages/receivePO/index.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/receivePO/index.blade.php  -->

    {{-- PurchaseOrder filter fields --}}
    {!! Form::model($purchaseOrder, ['class' => 'form-horizontal', 'method' => 'post', 'action' => 'Receive\ReceivePOController@filter']) !!}

        @include('pages.purchaseOrder.filter', ['labelType' => 'filter', 'submitButtonName' => 'Receive_PO_Filter'])

    {!! Form::close() !!}

    <!-- stop of pages/receivePO/index.blade.php, section('form') -->
@stop

@section('list')
    <!-- section('list') of pages/receivePO/index.blade.php  -->

    {{--  filtered list --}}
    @include('pages.purchaseOrder.list', ['route' => 'receivePO.show'])

    <!-- stop of pages/receivePO/index.blade.php, section('list') -->
@stop
