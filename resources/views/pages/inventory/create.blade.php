@extends('pages.panel')

@section('panel')
<!-- section('panel') of pages/inventory/create.blade.php  -->

    <div class="panel-heading">@lang('labels.titles.Create_Inventory')</div>
    <div class="panel-body">

        {{-- "partials", root directory is views, so 'errors.list' becomes views/errors/list.blade.php --}}
        @include('errors.list')

        {!! Form::open(['class' => 'form-horizontal', 'url' => 'inventory']) !!}

            @include('pages.inventory.form', ['submitButtonName' => 'Add_Inventory'])

        {!! Form::close() !!}

    </div>

<!-- stop of pages/inventory/create.blade.php, section('panel') -->
@stop
