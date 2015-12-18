@extends('pages.panelList')

@section('title')
    <!-- section('title') of pages/user/index.blade.php  -->

    @lang('labels.titles.Users')

    <!-- stop of pages/user/index.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/user/index.blade.php  -->

    @lang('labels.titles.User_Filter')

    <!-- stop of pages/user/index.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/user/index.blade.php  -->

    {{-- Filter fields --}}
    {!! Form::model($user, ['class' => 'form-horizontal', 'method' => 'patch', 'action' => 'UserController@filter']) !!}

        @include('pages.user.filter', ['labelType' => 'filter', 'submitButtonName' => 'User_Filter'])

    {!! Form::close() !!}

    <!-- stop of pages/user/index.blade.php, section('form') -->
@stop

@section('list')
    <!-- section('list') of pages/user/index.blade.php  -->

    {{-- Filtered list --}}
    @include('pages.user.list')

    <!-- stop of pages/user/index.blade.php, section('list') -->
@stop
