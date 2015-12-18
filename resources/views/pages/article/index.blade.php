@extends('pages.panelList')

@section('title')
    <!-- section('title') of pages/article/index.blade.php  -->

    @lang('labels.titles.Articles')

    <!-- stop of pages/article/index.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('title') of pages/article/index.blade.php  -->

    @lang('labels.titles.Article_Filter')

    <!-- stop of pages/article/index.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/article/index.blade.php  -->

    {{-- Filter fields --}}
    {!! Form::model($article, ['class' => 'form-horizontal', 'method' => 'patch', 'action' => 'vital40\ArticleController@filter']) !!}

        @include('pages.article.filter', ['labelType' => 'filter', 'submitButtonName' => 'Article_Filter'])

    {!! Form::close() !!}

    <!-- stop of pages/article/index.blade.php, section('form') -->
@stop

@section('list')
    <!-- section('list') of pages/article/index.blade.php  -->

    {{-- Filtered list --}}
    @include('pages.article.list')

    <!-- stop of pages/article/index.blade.php, section('list') -->
@stop
