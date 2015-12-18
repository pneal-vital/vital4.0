@extends('pages.panelList')

@section('title')
    <!-- section('title') of pages/inboundOrderDetail/index.blade.php  -->

    @lang('labels.titles.InboundOrderDetails')

    <!-- stop of pages/inboundOrderDetail/index.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/inboundOrderDetail/index.blade.php  -->

    @lang('labels.titles.InboundOrderDetail_Filter')

    <!-- stop of pages/inboundOrderDetail/index.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/inboundOrderDetail/index.blade.php  -->

    {{-- Filter fields --}}
    {!! Form::model($inboundOrderDetail, ['class' => 'form-horizontal', 'method' => 'patch', 'action' => 'vital3\InboundOrderDetailController@filter']) !!}

        @include('pages.inboundOrderDetail.filter', ['labelType' => 'filter', 'submitButtonName' => 'InboundOrderDetail_Filter'])

    {!! Form::close() !!}

    <!-- stop of pages/inboundOrderDetail/index.blade.php, section('form') -->
@stop

@section('list')
    <!-- section('list') of pages/inboundOrderDetail/index.blade.php  -->

    {{-- Filtered list --}}
    @include('pages.inboundOrderDetail.list')

<!-- stop of pages/inboundOrderDetail/index.blade.php, section('list') -->
@stop

