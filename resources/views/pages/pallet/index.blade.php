@extends('pages.panelList')

@section('title')
    <!-- section('title') of pages/pallet/index.blade.php  -->

    @lang('labels.titles.Pallet')

    <!-- stop of pages/pallet/index.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/pallet/index.blade.php  -->

    @lang('labels.titles.Pallet_Filter')

    <!-- stop of pages/pallet/index.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/pallet/index.blade.php  -->

    {{-- Filter fields --}}
    {!! Form::model($pallet, ['class' => 'form-horizontal', 'method' => 'patch', 'action' => 'PalletController@filter']) !!}

        @include('pages.pallet.filter', ['labelType' => 'filter', 'submitButtonName' => 'Pallet_Filter'])

    {!! Form::close() !!}

    <!-- stop of pages/pallet/index.blade.php, section('form') -->
@stop

@section('list')
    <!-- section('list') of pages/pallet/index.blade.php  -->

    {{-- Filtered list --}}
    @include('pages.pallet.list')

    <!-- stop of pages/pallet/index.blade.php, section('list') -->
@stop
