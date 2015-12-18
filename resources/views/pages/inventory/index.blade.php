@extends('pages.panelList')

@section('title')
    <!-- section('title') of pages/inventory/index.blade.php  -->

    @lang('labels.titles.Inventories')

    <!-- stop of pages/inventory/index.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/inventory/index.blade.php  -->

    @lang('labels.titles.Inventory_Filter')

    <!-- stop of pages/inventory/index.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/inventory/index.blade.php  -->

    {{-- Filter fields --}}
    {!! Form::model($inventory, ['class' => 'form-horizontal', 'method' => 'patch', 'action' => 'InventoryController@filter']) !!}

        @include('pages.inventory.filter', ['labelType' => 'filter', 'submitButtonName' => 'Inventory_Filter'])

    {!! Form::close() !!}

    <!-- stop of pages/inventory/index.blade.php, section('form') -->
@stop

@section('list')
    <!-- section('list') of pages/inventory/index.blade.php  -->

    {{-- Filtered list --}}
    @include('pages.inventory.list')

    <!-- stop of pages/inventory/index.blade.php, section('list') -->
@stop

