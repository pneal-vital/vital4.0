@extends('pages.panelList')

@section('title')
    <!-- section('title') of pages/receiveLocation/index.blade.php  -->

    @lang('labels.titles.Receive_Locations')

    <!-- stop of pages/receiveLocation/index.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/receiveLocation/index.blade.php  -->

    @lang('labels.titles.Receive_Location_Filter')

    <!-- stop of pages/receiveLocation/index.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/receiveLocation/index.blade.php  -->

    {{-- PurchaseOrder filter fields --}}
    {!! Form::model($location, ['class' => 'form-horizontal', 'method' => 'post', 'action' => 'Receive\ReceiveLocationController@filter']) !!}

        @include('pages.location.filter', ['labelType' => 'filter', 'submitButtonName' => 'Receive_Location_Filter'])

    {!! Form::close() !!}

    <!-- stop of pages/receiveLocation/index.blade.php, section('form') -->
@stop

@section('list')
    <!-- section('list') of pages/receiveLocation/index.blade.php  -->

    {{--  filtered list --}}
    @include('pages.location.list', ['route' => 'receiveLocation.show'])

    <!-- stop of pages/receiveLocation/index.blade.php, section('list') -->
@stop
