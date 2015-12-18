@extends('pages.panel')

@section('panel')
    <!-- section('panel') of pages/receiptHistory/edit.blade.php  -->

    <div class="panel-heading">@lang('labels.titles.Edit_ReceiptHistory')</div>
    <div class="panel-body">

        @include('errors.list')

        {{-- Using form-model binding --}}
        {!! Form::model($receiptHistory, ['class' => 'form-horizontal', 'method' => 'PATCH', 'action' => ['ReceiptHistoryController@update', $receiptHistory->activityID]]) !!}

            @include('pages.receiptHistory.form', ['submitButtonName' => 'Update_ReceiptHistory'])

        {!! Form::close() !!}

    </div>

<!-- stop of pages/receiptHistory/edit.blade.php, section('panel') -->
@stop

