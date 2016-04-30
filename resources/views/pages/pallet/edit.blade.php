@extends('pages.panel')

@section('panel')
    <!-- section('panel') of pages/pallet/edit.blade.php  -->

    <div class="panel-heading">@lang('labels.titles.Edit_Pallet')</div>
    <div class="panel-body">

        @include('errors.list')

        {{-- Using form-model binding --}}
        {!! Form::model($pallet, ['class' => 'form-horizontal', 'method' => 'PATCH', 'action' => ['PalletController@update', $pallet->objectID]]) !!}

            @include('pages.pallet.form', ['submitButtonName' => 'Update_Pallet'])

        {!! Form::close() !!}

    </div>

    @if(isset($inLocation))
        <div class="panel-heading">@lang('labels.titles.in_Location')</div>
        <div class="panel-body">

            {!! Form::model($inLocation, ['class' => 'form-horizontal', 'method' => 'get', 'action' => ['PalletController@move', $pallet->objectID]]) !!}

                @include('fields.textEntry', ['fieldName' => 'Location_Name', 'readonly' => 'readonly'])
                @include('fields.textEntry', ['fieldName' => 'LocType'      , 'readonly' => 'readonly'])
                @include('fields.textEntry', ['fieldName' => 'Comingle'     , 'readonly' => 'readonly'])

                @if($pallet->Status == 'LOCK')
                    @include('fields.button', ['submitButtonName' => 'Move_It', 'submitButtonClass' => 'btn-primary disabled'])
                @else
                    @include('fields.button', ['submitButtonName' => 'Move_It'])
                @endif

            {!! Form::close() !!}

        </div>
    @else
        <div class="panel-heading">@lang('labels.titles.Move_Pallet')</div>
        <div class="panel-body">

            {{-- Filter fields --}}
            {!! Form::model($location, ['class' => 'form-horizontal', 'method' => 'get', 'action' => ['PalletController@move', $pallet->objectID]]) !!}

                @include('pages.location.filter', ['labelType' => 'filter', 'submitButtonName' => 'Location_Filter'])

            {!! Form::close() !!}

            @if(isset($locations) && count($locations))
                {{-- Filtered list --}}
                @include('pages.location.list', ['hideCEDIcons' => 'true', 'routeName' => 'pallet.locate', 'pltID' => $pallet->objectID])
            @endif

        </div>
    @endif

<!-- stop of pages/pallet/edit.blade.php, section('panel') -->
@stop

