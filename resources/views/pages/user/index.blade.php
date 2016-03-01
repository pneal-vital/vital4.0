@extends('pages.panelList')

@section('title')
    <!-- section('title') of pages/user/index.blade.php  -->

    @lang('labels.titles.Users')

    <!-- stop of pages/user/index.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/user/index.blade.php  -->

    @lang('labels.titles.User_Filter')

    <div class="pull-right">

    @if(Entrust::can(['user.create']))
        <a href="{{URL::route('user.create')}}" title="{{ Lang::get('labels.icons.create') }}">{!! Html::image('img/create.png', Lang::get('labels.icons.create'),array('height'=>'20','width'=>'20')) !!}</a>
    @endif

    </div>

    <!-- stop of pages/user/index.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/user/index.blade.php  -->

    {{-- Filter fields --}}
    {!! Form::model($user, ['class' => 'form-horizontal', 'method' => 'patch', 'action' => 'UserController@filter']) !!}

        @include('pages.user.filter', ['labelType' => 'filter', 'submitButtonName' => 'User_Filter'])

    {!! Form::close() !!}

    <!-- stop of pages/user/index.blade.php, section('form') -->
@stop

@section('list')
    <!-- section('list') of pages/user/index.blade.php  -->

    {{-- Filtered list --}}
    @include('pages.user.list')

    <!-- stop of pages/user/index.blade.php, section('list') -->
@stop
