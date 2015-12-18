@extends('pages.panelList')

@section('title')
    <!-- section('title') of pages/associateNumber/index.blade.php  -->

    @lang('labels.titles.AssociateNumber')

    <!-- stop of pages/associateNumber/index.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/associateNumber/index.blade.php  -->

    @lang('labels.titles.AssociateNumber_Filter')

    <!-- stop of pages/associateNumber/index.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/associateNumber/index.blade.php  -->

    {{-- Filter fields --}}
    {!! Form::model($associateNumber, ['class' => 'form-horizontal', 'method' => 'patch', 'action' => 'AssociateNumberController@filter']) !!}

        @include('pages.associateNumber.filter', ['labelType' => 'filter', 'submitButtonName' => 'AssociateNumber_Filter'])

    {!! Form::close() !!}

    <!-- stop of pages/associateNumber/index.blade.php, section('form') -->
@stop

@section('list')
    <!-- section('list') of pages/associateNumber/index.blade.php  -->

    {{-- Filtered list --}}
    @include('pages.associateNumber.list')

    <!-- stop of pages/associateNumber/index.blade.php, section('list') -->
@stop

@section('footer')
    <!-- section('footer') of pages/associateNumber/index.blade.php  -->

    @if(count($associateNumbers))
        {{-- Export form --}}
        {!! Form::model($associateNumber, ['class' => 'form-horizontal', 'method' => 'patch', 'action' => 'AssociateNumberController@export']) !!}

            @include('fields.ddList', ['fieldName' => 'ExportType', 'lists' => $exportTypes, 'fieldSize' => 'col-md-6'])

            @include('fields.button', ['submitButtonName' => 'AssociateNumber_Export'])

        {!! Form::close() !!}
    @endif

    <!-- stop of pages/associateNumber/index.blade.php, section('footer') -->
@stop
