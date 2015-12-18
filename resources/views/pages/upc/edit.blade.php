@extends('pages.panel')

@section('panel')
    <!-- section('panel') of pages/upc/edit.blade.php  -->

    <div class="panel-heading">@lang('labels.titles.Edit_UPC')</div>
    <div class="panel-body">

        @include('errors.list')

        {{-- Using form-model binding --}}
        {!! Form::model($upc, ['class' => 'form-horizontal', 'method' => 'PATCH', 'action' => ['vital40\UPCController@update', $upc->objectID]]) !!}

            @include('pages.upc.form', ['submitButtonName' => 'Update_UPC'])

        {!! Form::close() !!}

    </div>

<!-- stop of pages/upc/edit.blade.php, section('panel') -->
@stop

