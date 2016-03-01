@extends('pages.panel')

@section('panel')
    <!-- section('panel') of pages/userRoles/create.blade.php  -->

    <div class="panel-heading">@lang('labels.titles.Create_UserRoles')</div>
    <div class="panel-body">

        @include('errors.list')

        {!! Form::open(['class' => 'form-horizontal', 'url' => 'userRoles']) !!}

            @include('pages.userRoles.form', ['submitButtonName' => 'Add_UserRoles'])

        {!! Form::close() !!}

    </div>

<!-- stop of pages/userRoles/create.blade.php, section('panel') -->
@stop

