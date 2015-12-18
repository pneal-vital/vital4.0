@extends('pages.panel')

@section('panel')
<!-- section('panel') of pages/article/create.blade.php  -->

    <div class="panel-heading">@lang('labels.titles.Create_Article')</div>
    <div class="panel-body">

        @include('errors.list')

        {!! Form::open(['class' => 'form-horizontal', 'url' => 'article']) !!}

            @include('pages.article.form', ['submitButtonName' => 'Add_Article'])

        {!! Form::close() !!}

    </div>

<!-- stop of pages/article/create.blade.php, section('panel') -->
@stop

