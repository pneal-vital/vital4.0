@extends('pages.panelList')

@section('title')
    <!-- section('title') of pages/userRoles/index.blade.php  -->

    @lang('labels.titles.UserRole')

    <!-- stop of pages/userRoles/index.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/userRoles/index.blade.php  -->

    @lang('labels.titles.UserRole_Filter')

    <div class="pull-right">

    @if(Entrust::can(['user.edit']))
        @if(isset($userRole['user_id']) && $userRole['user_id'] > 0)
            <a href="{{URL::route('userRoles.edit',['id' => $userRole['user_id']])}}" title="{{ Lang::get('labels.icons.edit') }}">{!! Html::image('img/edit.jpeg', Lang::get('labels.icons.edit'),array('height'=>'20','width'=>'20')) !!}</a>
        @endif
    @endif

    </div>

    <!-- stop of pages/userRoles/index.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/userRoles/index.blade.php  -->

    {{-- Filter fields --}}
    {!! Form::model($userRole, ['class' => 'form-horizontal', 'method' => 'patch', 'action' => 'UserRolesController@filter']) !!}

        @include('pages.userRoles.filter', ['labelType' => 'filter', 'submitButtonName' => 'UserRole_Filter'])

    {!! Form::close() !!}

    <!-- stop of pages/userRoles/index.blade.php, section('form') -->
@stop

@section('list')
    <!-- section('list') of pages/userRoles/index.blade.php  -->

    {{-- Filtered list --}}
    @include('pages.userRoles.list')

    <!-- stop of pages/userRoles/index.blade.php, section('list') -->
@stop
