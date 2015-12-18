@extends('pages.panel')

@section('panel')
    <!-- section('panel') of pages/tote/create.blade.php  -->

    <div class="panel-heading">@lang('labels.titles.Create_Tote')</div>
    <div class="panel-body">

        @include('errors.list')

        {!! Form::open(['class' => 'form-horizontal', 'url' => 'tote']) !!}

            @include('pages.tote.form', ['submitButtonName' => 'Add_Tote'])

        {!! Form::close() !!}

    </div>

<!-- stop of pages/tote/create.blade.php, section('panel') -->
@stop

