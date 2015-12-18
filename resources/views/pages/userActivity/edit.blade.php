@extends('pages.panel')

@section('panel')
    <!-- section('panel') of pages/userActivity/edit.blade.php  -->

    <div class="panel-heading">@lang('labels.titles.Edit_UserActivity')</div>
    <div class="panel-body">

        @include('errors.list')

        {{-- Using form-model binding --}}
        {!! Form::model($userActivity, ['class' => 'form-horizontal', 'method' => 'PATCH', 'action' => ['UserActivityController@update', $userActivity->activityID]]) !!}

            @include('pages.userActivity.form', ['submitButtonName' => 'Update_UserActivity'])

        {!! Form::close() !!}

    </div>

<!-- stop of pages/userActivity/edit.blade.php, section('panel') -->
@stop

