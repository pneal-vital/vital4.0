@extends('pages.panel')

@section('panel')
    <!-- section('panel') of pages/performanceTally/edit.blade.php  -->

    <div class="panel-heading">@lang('labels.titles.Edit_PerformanceTally')</div>
    <div class="panel-body">

        @include('errors.list')

        {{-- Using form-model binding --}}
        {!! Form::model($performanceTally, ['class' => 'form-horizontal', 'method' => 'PATCH', 'action' => ['PerformanceTallyController@update', $performanceTally->recordID]]) !!}

            @include('pages.performanceTally.form', ['submitButtonName' => 'Update_PerformanceTally'])

        {!! Form::close() !!}

    </div>

<!-- stop of pages/performanceTally/edit.blade.php, section('panel') -->
@stop

