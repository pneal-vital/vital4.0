@extends('pages.panel')

@section('panel')
    <!-- section('panel') of pages/userConversation/create.blade.php  -->

    <div class="panel-heading">@lang('labels.titles.Create_UserConversation')</div>
    <div class="panel-body">

        @include('errors.list')

        {!! Form::open(['class' => 'form-horizontal', 'url' => 'userConversation']) !!}

            @include('pages.userConversation.form', ['submitButtonName' => 'Add_UserConversation'])

        {!! Form::close() !!}

    </div>

<!-- stop of pages/userConversation/create.blade.php, section('panel') -->
@stop

