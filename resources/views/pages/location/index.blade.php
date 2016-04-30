@extends('pages.panelList')

@section('title')
    <!-- section('title') of pages/location/index.blade.php  -->

    @lang('labels.titles.Location')

    <!-- stop of pages/location/index.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/location/index.blade.php  -->

    @lang('labels.titles.Location_Filter')

    @if(Entrust::can(['location.create']))
        <div class="pull-right">
            <a href="{{URL::route('location.create')}}" title="{{ Lang::get('labels.icons.create') }}">{!! Html::image('img/create.png', Lang::get('labels.icons.create'),array('height'=>'20','width'=>'20')) !!}</a>
        </div>
    @endif

    <!-- stop of pages/location/index.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/location/index.blade.php  -->

    {{-- Filter fields --}}
    {!! Form::model($location, ['class' => 'form-horizontal', 'method' => 'patch', 'action' => 'LocationController@filter']) !!}

        @include('pages.location.filter', ['labelType' => 'filter', 'submitButtonName' => 'Location_Filter'])

    {!! Form::close() !!}

    <!-- stop of pages/location/index.blade.php, section('form') -->
@stop

@section('list')
    <!-- section('list') of pages/location/index.blade.php  -->

    {{-- Filtered list --}}
    @include('pages.location.list')

    <!-- stop of pages/location/index.blade.php, section('list') -->
@stop
