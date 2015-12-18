@extends('pages.panel')

@section('panel')
    <!-- section('panel') of pages/upc/create.blade.php  -->

    <div class="panel-heading">@lang('labels.titles.Create_UPC')</div>
    <div class="panel-body">

        @include('errors.list')

        {!! Form::open(['class' => 'form-horizontal', 'url' => 'upc']) !!}

            @include('pages.upc.form', ['submitButtonName' => 'Add_UPC'])

        {!! Form::close() !!}

    </div>

<!-- stop of pages/upc/create.blade.php, section('panel') -->
@stop

