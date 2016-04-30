@extends('pages.panelList')

@section('title')
    <!-- section('title') of pages/inventory/index.blade.php  -->

    @lang('labels.titles.Inventories')

    <!-- stop of pages/inventory/index.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/inventory/index.blade.php  -->

    @lang('labels.titles.Inventory_Filter')

    @if(Entrust::can(['pallet.create']))
        <div class="pull-right">
            <a href="{{URL::route('inventory.create')}}" title="{{ Lang::get('labels.icons.create') }}">{!! Html::image('img/create.png', Lang::get('labels.icons.create'),array('height'=>'20','width'=>'20')) !!}</a>
        </div>
    @endif

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

