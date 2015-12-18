@extends('pages.panel')

@section('panel')
    <!-- section('panel') of pages/userConversation/edit.blade.php  -->

    <div class="panel-heading">@lang('labels.titles.Edit_UserConversation')</div>
    <div class="panel-body">

        @include('errors.list')

        {{-- Using form-model binding --}}
        {!! Form::model($userConversation, ['class' => 'form-horizontal', 'method' => 'PATCH', 'action' => ['UserConversationController@update', $userConversation->activityID]]) !!}

            @include('pages.userConversation.form', ['submitButtonName' => 'Update_UserConversation'])

        {!! Form::close() !!}

    </div>

<!-- stop of pages/userConversation/edit.blade.php, section('panel') -->
@stop

