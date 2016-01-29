@extends('pages.panelList')

@section('title')
    <!-- section('title') of pages/rolePermissions/index.blade.php  -->

    @lang('labels.titles.RolePermission')

    <!-- stop of pages/rolePermissions/index.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/rolePermissions/index.blade.php  -->

    @lang('labels.titles.RolePermission_Filter')

    <div class="pull-right">

    @if(Entrust::can(['role.edit']))
        @if(isset($rolePermission['role_id']) && $rolePermission['role_id'] > 0)
            <a href="{{URL::route('rolePermissions.edit',['id' => $rolePermission['role_id']])}}" title="{{ Lang::get('labels.icons.edit') }}">{!! Html::image('img/edit.jpeg', Lang::get('labels.icons.edit'),array('height'=>'20','width'=>'20')) !!}</a>
        @endif
    @endif

    </div>

    <!-- stop of pages/rolePermissions/index.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/rolePermissions/index.blade.php  -->

    {{-- Filter fields --}}
    {!! Form::model($rolePermission, ['class' => 'form-horizontal', 'method' => 'patch', 'action' => 'RolePermissionsController@filter']) !!}

        @include('pages.rolePermissions.filter', ['labelType' => 'filter', 'submitButtonName' => 'RolePermission_Filter'])

    {!! Form::close() !!}

    <!-- stop of pages/rolePermissions/index.blade.php, section('form') -->
@stop

@section('list')
    <!-- section('list') of pages/rolePermissions/index.blade.php  -->

    {{-- Filtered list --}}
    @include('pages.rolePermissions.list')

    <!-- stop of pages/rolePermissions/index.blade.php, section('list') -->
@stop
