@extends('pages.panel')

@section('panel')
<!-- section('panel') of pages/inboundOrder/create.blade.php  -->

    <div class="panel-heading">@lang('labels.titles.Create_InboundOrder')</div>
    <div class="panel-body">

        {{-- "partials", root directory is views, so 'errors.list' becomes views/errors/list.blade.php --}}
        @include('errors.list')

        {!! Form::open(['class' => 'form-horizontal', 'url' => 'inboundOrder']) !!}

            @include('pages.inboundOrder.form', ['submitButtonName' => 'Add_InboundOrder'])

        {!! Form::close() !!}

    </div>

<!-- stop of pages/inboundOrder/create.blade.php, section('panel') -->
@stop
