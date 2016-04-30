@extends('pages.panelList')

@section('title')
    <!-- section('title') of pages/tote/index.blade.php  -->

    @lang('labels.titles.Tote')

    <!-- stop of pages/tote/index.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/tote/index.blade.php  -->

    @lang('labels.titles.Tote_Filter')

    @if(Entrust::can(['pallet.create']))
        <div class="pull-right">
            <a href="{{URL::route('tote.create')}}" title="{{ Lang::get('labels.icons.create') }}">{!! Html::image('img/create.png', Lang::get('labels.icons.create'),array('height'=>'20','width'=>'20')) !!}</a>
        </div>
    @endif

    <!-- stop of pages/tote/index.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/tote/index.blade.php  -->

    {{-- Filter fields --}}
    {!! Form::model($tote, ['class' => 'form-horizontal', 'method' => 'patch', 'action' => 'ToteController@filter']) !!}

        @include('pages.tote.filter', ['labelType' => 'filter', 'submitButtonName' => 'Tote_Filter'])

    {!! Form::close() !!}

    <!-- stop of pages/tote/index.blade.php, section('form') -->
@stop

@section('list')
    <!-- section('list') of pages/tote/index.blade.php  -->

    {{-- Filtered list --}}
    @include('pages.tote.list')

    <!-- stop of pages/tote/index.blade.php, section('list') -->
@stop
