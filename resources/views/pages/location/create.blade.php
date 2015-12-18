@extends('pages.panel')

@section('panel')
    <!-- section('panel') of pages/location/create.blade.php  -->

    <div class="panel-heading">@lang('labels.titles.Create_Location')</div>
    <div class="panel-body">

        @include('errors.list')

        {!! Form::open(['class' => 'form-horizontal', 'url' => 'location']) !!}

            @include('pages.location.form', ['submitButtonName' => 'Add_Location'])

        {!! Form::close() !!}

    </div>

<!-- stop of pages/location/create.blade.php, section('panel') -->
@stop

