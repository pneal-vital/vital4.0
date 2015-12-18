@extends('pages.panelList')

@section('title')
    <!-- section('title') of pages/userActivity/index.blade.php  -->

    @if(\Auth::user() && \Auth::user()->name == $userActivity['User_Name'])
        @lang('labels.titles.MyActivities')
    @else
        @lang('labels.titles.UserActivities')
    @endif

    <!-- stop of pages/userActivity/index.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/userActivity/index.blade.php  -->

    @lang('labels.titles.UserActivity_Filter')

    <!-- stop of pages/userActivity/index.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/userActivity/index.blade.php  -->

    {{-- Filter fields --}}
    {!! Form::model($userActivity, ['class' => 'form-horizontal', 'method' => 'patch', 'action' => 'UserActivityController@filter']) !!}

        @include('pages.userActivity.filter', ['labelType' => 'filter', 'submitButtonName' => 'UserActivity_Filter'])

    {!! Form::close() !!}

    <!-- stop of pages/userActivity/index.blade.php, section('form') -->
@stop

@section('list')
    <!-- section('list') of pages/userActivity/index.blade.php  -->

    {{-- Filtered list --}}
    @include('pages.userActivity.list')

    <!-- stop of pages/userActivity/index.blade.php, section('list') -->
@stop
