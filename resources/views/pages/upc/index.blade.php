@extends('pages.panelList')

@section('title')
    <!-- section('title') of pages/upc/index.blade.php  -->

    @lang('labels.titles.UPC')

    <!-- stop of pages/upc/index.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/upc/index.blade.php  -->

    @lang('labels.titles.UPC_Filter')

    <!-- stop of pages/upc/index.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/upc/index.blade.php  -->

    {{-- Filter fields --}}
    {!! Form::model($upc, ['class' => 'form-horizontal', 'method' => 'patch', 'action' => 'vital40\UPCController@filter']) !!}

        @include('pages.upc.filter', ['labelType' => 'filter', 'submitButtonName' => 'UPC_Filter'])

    {!! Form::close() !!}

    <!-- stop of pages/upc/index.blade.php, section('form') -->
@stop

@section('list')
    <!-- section('list') of pages/upc/index.blade.php  -->

    {{-- Filtered list --}}
    @include('pages.upc.list')

    <!-- stop of pages/upc/index.blade.php, section('list') -->
@stop
