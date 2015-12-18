@extends('pages.panel')

@section('panel')
    <!-- section('panel') of pages/inboundOrderDetail/edit.blade.php  -->

    <div class="panel-heading">@lang('labels.titles.Edit_InboundOrderDetail')</div>
    <div class="panel-body">

        @include('errors.list')

        {{-- Using form-model binding --}}
        {!! Form::model($inboundOrderDetail, ['class' => 'form-horizontal', 'method' => 'PATCH', 'action' => ['vital3\InboundOrderDetailController@update', $inboundOrderDetail->objectID]]) !!}

            @include('pages.inboundOrderDetail.form', ['submitButtonName' => 'Update_InboundOrderDetail'])

        {!! Form::close() !!}

    </div>

<!-- stop of pages/inboundOrderDetail/edit.blade.php, section('panel') -->
@stop

