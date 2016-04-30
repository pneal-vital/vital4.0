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

    @if(isset($inTote))
        <div class="panel-heading">@lang('labels.titles.in_Tote')</div>
        <div class="panel-body">

            {!! Form::model($inTote, ['class' => 'form-horizontal', 'method' => 'get', 'action' => ['InventoryController@move', $inventory->objectID]]) !!}

                @include('fields.textEntry', ['fieldName' => 'Carton_ID', 'readonly' => 'readonly'])
                @include('fields.textEntry', ['fieldName' => 'Status'   , 'readonly' => 'readonly'])

                @include('fields.button', ['submitButtonName' => 'Move_It'])

            {!! Form::close() !!}

        </div>
    @else
        <div class="panel-heading">@lang('labels.titles.Move_Inventory')</div>
        <div class="panel-body">

            {{-- Filter fields --}}
            {!! Form::model($tote, ['class' => 'form-horizontal', 'method' => 'get', 'action' => ['InventoryController@move', $inventory->objectID]]) !!}

                @include('pages.tote.filter', ['labelType' => 'filter', 'submitButtonName' => 'Tote_Filter'])

            {!! Form::close() !!}

            @if(isset($totes) && count($totes))
                {{-- Filtered list --}}
                @include('pages.tote.list', ['hideCEDIcons' => 'true', 'routeName' => 'inventory.locate', 'invID' => $inventory->objectID])
            @endif

        </div>
    @endif

<!-- stop of pages/inventory/edit.blade.php, section('panel') -->
@stop

