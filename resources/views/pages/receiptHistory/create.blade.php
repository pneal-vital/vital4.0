@extends('pages.panel')

@section('panel')
    <!-- section('panel') of pages/receiptHistory/create.blade.php  -->

    <div class="panel-heading">@lang('labels.titles.Create_ReceiptHistory')</div>
    <div class="panel-body">

        @include('errors.list')

        {!! Form::open(['class' => 'form-horizontal', 'url' => 'receiptHistory']) !!}

            @include('pages.receiptHistory.form', ['submitButtonName' => 'Add_ReceiptHistory'])

        {!! Form::close() !!}

    </div>

<!-- stop of pages/receiptHistory/create.blade.php, section('panel') -->
@stop

