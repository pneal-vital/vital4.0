@extends('pages.panel')

@section('panel')
<!-- section('panel') of pages/inventory/edit.blade.php  -->

    <div class="panel-heading">@lang('labels.titles.Edit_Inventory')</div>
    <div class="panel-body">

        {{-- "partials", root directory is views, so 'errors.list' becomes views/errors/list.blade.php --}}
        @include('errors.list')

        {{-- Using form-model binding --}}
        {!! Form::model($inventory, ['class' => 'form-horizontal', 'method' => 'PATCH', 'action' => ['InventoryController@update', $inventory->objectID]]) !!}

            @include('pages.inventory.form', ['submitButtonName' => 'Update_Inventory'])

        {!! Form::close() !!}

    </div>

<!-- stop of pages/inventory/edit.blade.php, section('panel') -->
@stop

