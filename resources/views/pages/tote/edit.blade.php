@extends('pages.panel')

@section('panel')
    <!-- section('panel') of pages/tote/edit.blade.php  -->

    <div class="panel-heading">@lang('labels.titles.Edit_Tote')</div>
    <div class="panel-body">

        @include('errors.list')

        {{-- Using form-model binding --}}
        {!! Form::model($tote, ['class' => 'form-horizontal', 'method' => 'PATCH', 'action' => ['ToteController@update', $tote->objectID]]) !!}

            @include('pages.tote.form', ['submitButtonName' => 'Update_Tote'])

        {!! Form::close() !!}

    </div>

<!-- stop of pages/tote/edit.blade.php, section('panel') -->
@stop

