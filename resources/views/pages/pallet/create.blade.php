@extends('pages.panel')

@section('panel')
    <!-- section('panel') of pages/pallet/create.blade.php  -->

    <div class="panel-heading">@lang('labels.titles.Create_Pallet')</div>
    <div class="panel-body">

        @include('errors.list')

        {!! Form::open(['class' => 'form-horizontal', 'url' => 'pallet']) !!}

            @include('pages.pallet.form', ['submitButtonName' => 'Add_Pallet'])

        {!! Form::close() !!}

    </div>

<!-- stop of pages/pallet/create.blade.php, section('panel') -->
@stop

