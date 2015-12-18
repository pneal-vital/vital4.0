@extends('pages.panelList')

@section('title')
    <!-- section('title') of pages/userConversation/index.blade.php  -->

    @lang('labels.titles.UserConversations')

    <!-- stop of pages/userConversation/index.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/userConversation/index.blade.php  -->

    @lang('labels.titles.UserConversation_Filter')

    <!-- stop of pages/userConversation/index.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/userConversation/index.blade.php  -->

    {{-- Filter fields --}}
    {!! Form::model($userConversation, ['class' => 'form-horizontal', 'method' => 'patch', 'action' => 'UserConversationController@filter']) !!}

        @include('pages.userConversation.filter', ['labelType' => 'filter', 'submitButtonName' => 'UserConversation_Filter'])

    {!! Form::close() !!}

    <!-- stop of pages/userConversation/index.blade.php, section('form') -->
@stop

@section('list')
    <!-- section('list') of pages/userConversation/index.blade.php  -->

    {{-- Filtered list --}}
    @include('pages.userConversation.list')

    <!-- stop of pages/userConversation/index.blade.php, section('list') -->
@stop
