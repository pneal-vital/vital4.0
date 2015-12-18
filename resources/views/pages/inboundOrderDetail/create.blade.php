@extends('pages.panel')

@section('panel')
    <!-- section('panel') of pages/inboundOrderDetail/create.blade.php  -->

    <div class="panel-heading">@lang('labels.titles.Create_InboundOrderDetail')</div>
    <div class="panel-body">

        @include('errors.list')

        {!! Form::open(['class' => 'form-horizontal', 'url' => 'inboundOrderDetail']) !!}

            @include('pages.inboundOrderDetail.form', ['submitButtonName' => 'Add_InboundOrderDetail'])

        {!! Form::close() !!}

    </div>

<!-- stop of pages/inboundOrderDetail/create.blade.php, section('panel') -->
@stop

