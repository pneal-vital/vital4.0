@extends('pages.panel')

@section('panel')
    <!-- section('panel') of pages/userActivity/create.blade.php  -->

    <div class="panel-heading">@lang('labels.titles.Create_UserActivity')</div>
    <div class="panel-body">

        @include('errors.list')

        {!! Form::open(['class' => 'form-horizontal', 'url' => 'userActivity']) !!}

            @include('pages.userActivity.form', ['submitButtonName' => 'Add_UserActivity'])

        {!! Form::close() !!}

    </div>

<!-- stop of pages/userActivity/create.blade.php, section('panel') -->
@stop

