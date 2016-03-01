@extends('pages.panelList')

@section('head')
    <!-- section('head') of pages/role/show.blade.php  -->

    @include('fields.cedIcons', ['model' => 'role', 'elemType' => 'script'])

    <!-- stop of pages/role/show.blade.php, section('head') -->
@stop

@section('title')
    <!-- section('title') of pages/role/show.blade.php  -->

    @lang('labels.titles.Role')

    <!-- stop of pages/role/show.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/role/show.blade.php  -->

    <h4 class="panel-title pull-left">
        @lang('labels.titles.Role_for') {{ $role->name }}
    </h4>

    @include('fields.cedIcons', ['model' => 'role', 'elemType' => 'div', 'id' => $role->id])

    <!-- stop of pages/role/show.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/role/show.blade.php  -->

    <div class="form-group col-md-12 text-center">
        @lang('internal.role.allows', ['display_name' => $role->display_name, 'description' => $role->description])
    </div>

    {{--
    * Table Structure
    * desc roles;
    +--------------+------------------+------+-----+---------------------+----------------+
    | Field        | Type             | Null | Key | Default             | Extra          |
    +--------------+------------------+------+-----+---------------------+----------------+
    | id           | int(10) unsigned | NO   | PRI | NULL                | auto_increment |
    | name         | varchar(255)     | NO   | UNI | NULL                |                |
    | display_name | varchar(255)     | YES  |     | NULL                |                |
    | description  | varchar(255)     | YES  |     | NULL                |                |
    | created_at   | timestamp        | NO   |     | 0000-00-00 00:00:00 |                |
    | updated_at   | timestamp        | NO   |     | 0000-00-00 00:00:00 |                |
    +--------------+------------------+------+-----+---------------------+----------------+
    6 rows in set (0.01 sec)
    --}}

    @if(Entrust::hasRole(['support']))
        @include('fields.textList', ['fieldName' => 'id'      , 'fieldValue' => $role->id           ])
    @endif
    @include('fields.textList', ['fieldName' => 'name'        , 'fieldValue' => $role->name         ])
    @include('fields.textList', ['fieldName' => 'display_name', 'fieldValue' => $role->display_name ])
    @include('fields.textList', ['fieldName' => 'description' , 'fieldValue' => $role->description  ])
    @include('fields.textList', ['fieldName' => 'created_at'  , 'fieldValue' => $role->created_at   ])
    @if($role->updated_at > '0000-00-00')
        @include('fields.textList', ['fieldName' => 'updated_at', 'fieldValue' => $role->updated_at ])
    @else
        @include('fields.textList', ['fieldName' => 'updated_at', 'fieldValue' => '' ])
    @endif

    {!! Form::open(['class' => 'form-horizontal', 'url' => 'userRoles', 'method' => 'get']) !!}
    <input name="name" type="hidden" value="">
    <input name="role_id" type="hidden" value="{!! $role->id !!}">

    @include('fields.button', [ 'submitButtonName' => 'UsersWithRole' ])

    {!! Form::close() !!}

    <!-- stop of pages/role/show.blade.php, section('panel') -->
@stop

@section('list')
    <!-- section('list') of pages/role/show.blade.php  -->

    @if(isset($permissions) && count($permissions))
        <h3>{!! Lang::get('labels.titles.Permissions_for') !!} {!! $role->display_name !!}</h3>
    @endif

        <div class="pull-right">

            @if(Entrust::can(['role.edit']))
                <a href="{{URL::route('rolePermissions.edit',['id' => $role->id])}}" title="{{ Lang::get('labels.icons.editPermissions_for').' '.$role->display_name }}">{!! Html::image('img/edit.jpeg', Lang::get('labels.icons.edit'),array('height'=>'20','width'=>'20')) !!}</a>
            @endif

        </div>

    @if(isset($permissions) && count($permissions))
        <!-- reuse pages.permissions.list -->
        @include('pages.permission.list', ['simpleList' => 'True'])
    @endif

    <!-- stop of pages/role/show.blade.php, section('list') -->
@stop
