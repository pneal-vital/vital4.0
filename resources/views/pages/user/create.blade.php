@extends('pages.panel')

@section('panel')
    <!-- section('panel') of pages/user/create.blade.php  -->

    <div class="panel-heading">@lang('labels.titles.Create_User')</div>
    <div class="panel-body">

        @include('errors.list')

        {!! Form::open(['class' => 'form-horizontal', 'url' => 'user']) !!}

            @include('pages.user.form', ['submitButtonName' => 'Add_User'])

        {!! Form::close() !!}

    </div>

<!-- stop of pages/user/create.blade.php, section('panel') -->
@stop

