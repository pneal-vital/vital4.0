@extends('pages.panelList')

@section('head')
    <!-- section('head') of pages/user/show.blade.php  -->

    @include('fields.cedIcons', ['model' => 'user', 'elemType' => 'script'])

    <!-- stop of pages/user/show.blade.php, section('head') -->
@stop

@section('title')
    <!-- section('title') of pages/user/show.blade.php  -->

    @lang('labels.titles.User')

    <!-- stop of pages/user/show.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/user/show.blade.php  -->

    <h4 class="panel-title pull-left">
        @lang('labels.titles.User_for') {{ $user->name }}
    </h4>

    @include('fields.cedIcons', ['model' => 'user', 'elemType' => 'div', 'id' => $user->id])

    <!-- stop of pages/user/show.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/user/show.blade.php  -->

    {{--
    * Table Structure
    * desc users;
    +----------------+------------------+------+-----+---------------------+----------------+
    | Field          | Type             | Null | Key | Default             | Extra          |
    +----------------+------------------+------+-----+---------------------+----------------+
    | id             | int(10) unsigned | NO   | PRI | NULL                | auto_increment |
    | name           | varchar(255)     | NO   |     | NULL                |                |
    | email          | varchar(255)     | NO   | UNI | NULL                |                |
    | password       | varchar(60)      | NO   |     | NULL                |                |
    | remember_token | varchar(100)     | YES  |     | NULL                |                |
    | created_at     | timestamp        | NO   |     | 0000-00-00 00:00:00 |                |
    | updated_at     | timestamp        | NO   |     | 0000-00-00 00:00:00 |                |
    +----------------+------------------+------+-----+---------------------+----------------+
    7 rows in set (0.00 sec)
    --}}

    @if(Entrust::hasRole(['support']))
        @include('fields.textList', ['fieldName' => 'id'    , 'fieldValue' => $user->id         ])
    @endif
    @include('fields.textList', ['fieldName' => 'name'      , 'fieldValue' => $user->name       ])
    @include('fields.textList', ['fieldName' => 'email'     , 'fieldValue' => $user->email      ])
    @include('fields.textList', ['fieldName' => 'created_at', 'fieldValue' => $user->created_at ])
    @if($user->updated_at > '0000-00-00')
        @include('fields.textList', ['fieldName' => 'updated_at', 'fieldValue' => $user->updated_at ])
    @else
        @include('fields.textList', ['fieldName' => 'updated_at', 'fieldValue' => '' ])
    @endif

    <!-- stop of pages/user/show.blade.php, section('panel') -->
@stop


@section('list')
    <!-- section('list') of pages/user/show.blade.php  -->

    @if(isset($roles) && count($roles))
        <h3>{!! Lang::get('labels.titles.Roles_for') !!} {!! $user->name !!}</h3>
    @endif

    <div class="pull-right">

        @if(Entrust::can(['user.edit']))
            <a href="{{URL::route('userRoles.edit',['id' => $user->id])}}" title="{{ Lang::get('labels.icons.editRoles_for').' '.$user->name }}">{!! Html::image('img/edit.jpeg', Lang::get('labels.icons.edit'),array('height'=>'20','width'=>'20')) !!}</a>
        @endif

    </div>

    @if(isset($roles) && count($roles))
        <!-- reuse pages.roles.list -->
        @include('pages.role.list', ['simpleList' => 'True'])
    @endif

    <!-- stop of pages/user/show.blade.php, section('list') -->
@stop

