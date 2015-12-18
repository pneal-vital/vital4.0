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

<!-- stop of pages/pallet/edit.blade.php, section('panel') -->
@stop

