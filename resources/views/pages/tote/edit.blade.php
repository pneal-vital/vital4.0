@extends('pages.panel')

@section('panel')
    <!-- section('panel') of pages/tote/edit.blade.php  -->

    <div class="panel-heading">@lang('labels.titles.Edit_Tote')</div>
    <div class="panel-body">

        @include('errors.list')

        {{-- Using form-model binding --}}
        {!! Form::model($tote, ['class' => 'form-horizontal', 'method' => 'PATCH', 'action' => ['ToteController@update', $tote->objectID]]) !!}

            @include('pages.tote.form', ['submitButtonName' => 'Update_Tote'])

        {!! Form::close() !!}

    </div>

    @if(isset($inPallet))
        <div class="panel-heading">@lang('labels.titles.on_Pallet')</div>
        <div class="panel-body">
    
            {!! Form::model($inPallet, ['class' => 'form-horizontal', 'method' => 'get', 'action' => ['ToteController@move', $tote->objectID]]) !!}
    
                @include('fields.textEntry', ['fieldName' => 'Pallet_ID', 'readonly' => 'readonly'])
                @include('fields.textEntry', ['fieldName' => 'Status'   , 'readonly' => 'readonly'])
        
                @include('fields.button', ['submitButtonName' => 'Move_It'])

            {!! Form::close() !!}
    
        </div>
    @else
        <div class="panel-heading">@lang('labels.titles.Move_Tote')</div>
        <div class="panel-body">
    
            {{-- Filter fields --}}
            {!! Form::model($pallet, ['class' => 'form-horizontal', 'method' => 'get', 'action' => ['ToteController@move', $tote->objectID]]) !!}
    
                @include('pages.pallet.filter', ['labelType' => 'filter', 'submitButtonName' => 'Pallet_Filter'])
    
            {!! Form::close() !!}
    
            @if(isset($pallets) && count($pallets))
                {{-- Filtered list --}}
                @include('pages.pallet.list', ['hideCEDIcons' => 'true', 'routeName' => 'tote.locate', 'gcID' => $tote->objectID])
            @endif
    
        </div>
    @endif

<!-- stop of pages/tote/edit.blade.php, section('panel') -->
@stop

