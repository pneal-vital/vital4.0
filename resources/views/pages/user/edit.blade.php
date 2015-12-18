@extends('pages.panel')

@section('panel')
    <!-- section('panel') of pages/user/edit.blade.php  -->

    <div class="panel-heading">@lang('labels.titles.Edit_User')</div>
    <div class="panel-body">

        @include('errors.list')

        {{-- Using form-model binding --}}
        {!! Form::model($user, ['class' => 'form-horizontal', 'method' => 'PATCH', 'action' => ['UserController@update', $user->id]]) !!}

            @include('pages.user.form', ['submitButtonName' => 'Update_User'])

        {!! Form::close() !!}

    </div>

<!-- stop of pages/user/edit.blade.php, section('panel') -->
@stop

