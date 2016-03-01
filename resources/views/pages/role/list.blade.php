<!-- Beginning of pages/role/list.blade.php  -->

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

<table class="table">
    <tr>
        @if(Entrust::hasRole(['support']))
            <th>{!! Lang::get('labels.id')           !!}</th>
        @endif
        @if(isset($simpleList) == false)
            <th>{!! Lang::get('labels.usersHaveThis') !!}</th>
        @endif
        <th>{!! Lang::get('labels.name')         !!}</th>
        <th>{!! Lang::get('labels.display_name') !!}</th>
        <th>{!! Lang::get('labels.description')  !!}</th>
        @if(isset($simpleList) == false)
            <th>{!! Lang::get('labels.created_at')   !!}</th>
            <th>{!! Lang::get('labels.updated_at')   !!}</th>
            @include('fields.cedIcons', ['model' => 'role', 'elemType' => 'th'])
        @endif
    </tr>

    @foreach($roles as $r)
        <tr>
            @if(Entrust::hasRole(['support']))
                <td>{!! link_to_route('role.show', $r->id, ['id' => $r->id]) !!}</td>
            @endif
            @if(isset($simpleList) == false)
                <td>{!! link_to_route('userRoles.index', 'Users', ['name' => '', 'role_id' => $r->id]) !!}</td>
            @endif
            <td>{!! link_to_route('role.show', $r->name, ['id' => $r->id]) !!}</td>
            <td>{{ $r->display_name }}</td>
            <td>{{ $r->description  }}</td>
            @if(isset($simpleList) == false)
                <td>{{ $r->created_at   }}</td>
                <td>{{ $r->updated_at   }}</td>
                @include('fields.cedIcons', ['model' => 'role', 'elemType' => 'td', 'id' => $r->id])
            @endif
        </tr>
    @endforeach
</table>

@if(isset($simpleList) == false)
    {!! isset($role) ? $roles->appends($role)->render() : $roles->render() !!}

    @include('fields.cedIcons', ['model' => 'role', 'elemType' => 'script'])
@endif

<!-- End of pages/role/list.blade.php -->

