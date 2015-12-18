@extends('pages.panelList')

@section('title')
    <!-- section('title') of pages/productivityNumber/index.blade.php  -->

    @lang('labels.titles.ProductivityNumber')

    <!-- stop of pages/productivityNumber/index.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/productivityNumber/index.blade.php  -->

    @lang('labels.titles.ProductivityNumber_Filter')

    <!-- stop of pages/productivityNumber/index.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/productivityNumber/index.blade.php  -->

    {{-- Filter fields --}}
    {!! Form::model($productivityNumber, ['class' => 'form-horizontal', 'method' => 'patch', 'action' => 'ProductivityNumberController@filter']) !!}

        @include('pages.productivityNumber.filter', ['labelType' => 'filter', 'submitButtonName' => 'ProductivityNumber_Filter'])

    {!! Form::close() !!}

    <!-- stop of pages/productivityNumber/index.blade.php, section('form') -->
@stop

@section('list')
    <!-- section('list') of pages/productivityNumber/index.blade.php  -->

    {{-- Filtered list --}}
    @include('pages.productivityNumber.list')

    <!-- stop of pages/productivityNumber/index.blade.php, section('list') -->
@stop

@section('footer')
    <!-- section('footer') of pages/productivityNumber/index.blade.php  -->

    @if(count($productivityNumbers))
        {{-- Export form --}}
        {!! Form::model($productivityNumber, ['class' => 'form-horizontal', 'method' => 'patch', 'action' => 'ProductivityNumberController@export']) !!}

            @include('fields.ddList', ['fieldName' => 'ExportType', 'lists' => $exportTypes, 'fieldSize' => 'col-md-6'])

            @include('fields.button', ['submitButtonName' => 'ProductivityNumber_Export'])

        {!! Form::close() !!}
    @endif

    <!-- stop of pages/productivityNumber/index.blade.php, section('footer') -->
@stop
