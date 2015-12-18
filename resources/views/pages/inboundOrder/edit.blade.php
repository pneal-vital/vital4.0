@extends('pages.panel')

@section('panel')
<!-- section('panel') of pages/inboundOrder/edit.blade.php  -->

    <div class="panel-heading">@lang('labels.titles.Edit_InboundOrder')</div>
    <div class="panel-body">

        {{-- "partials", root directory is views, so 'errors.list' becomes views/errors/list.blade.php --}}
        @include('errors.list')

        {{-- Using form-model binding --}}
        {!! Form::model($inboundOrder, ['class' => 'form-horizontal', 'method' => 'PATCH', 'action' => ['vital3\InboundOrderController@update', $inboundOrder->objectID]]) !!}

            @include('pages.inboundOrder.form', ['submitButtonName' => 'Update_InboundOrder'])

        {!! Form::close() !!}

    </div>

<!-- stop of pages/inboundOrder/edit.blade.php, section('panel') -->
@stop

