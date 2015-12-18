@extends('pages.panel')

@section('panel')
    <!-- section('panel') of pages/article/edit.blade.php  -->

    <div class="panel-heading">@lang('labels.titles.Edit_Article')</div>
    <div class="panel-body">

        @include('errors.list')

        {{-- Using form-model binding --}}
        {!! Form::model($article, ['class' => 'form-horizontal', 'method' => 'PATCH', 'action' => ['vital40\ArticleController@update', $article->objectID]]) !!}

            @include('pages.article.form', ['submitButtonName' => 'Update_Article'])

        {!! Form::close() !!}

    </div>

<!-- stop of pages/article/edit.blade.php, section('panel') -->
@stop

