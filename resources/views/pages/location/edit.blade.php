@extends('pages.panel')

@section('panel')
    <!-- section('panel') of pages/location/edit.blade.php  -->

    <div class="panel-heading">@lang('labels.titles.Edit_Location')</div>
    <div class="panel-body">

        @include('errors.list')

        {{-- Using form-model binding --}}
        {!! Form::model($location, ['class' => 'form-horizontal', 'method' => 'PATCH', 'action' => ['LocationController@update', $location->objectID]]) !!}

            @include('pages.location.form', ['submitButtonName' => 'Update_Location'])

        {!! Form::close() !!}

    </div>

<!-- stop of pages/location/edit.blade.php, section('panel') -->
@stop

