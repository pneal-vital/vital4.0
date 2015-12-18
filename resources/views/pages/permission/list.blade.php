<!-- Beginning of pages/permission/list.blade.php  -->

{{--
    * Table Structure
    * desc permissions;
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
        <th>{!! Lang::get('labels.id')           !!}</th>
        <th>{!! Lang::get('labels.name')         !!}</th>
        <th>{!! Lang::get('labels.display_name') !!}</th>
        <th>{!! Lang::get('labels.description')  !!}</th>
        <th>{!! Lang::get('labels.created_at')   !!}</th>
        <th>{!! Lang::get('labels.updated_at')   !!}</th>
    </tr>

    @foreach($permissions as $prm)
        <tr>
            @if(Entrust::hasRole(['support']))
                <td>{!! link_to_route('permission.show', $prm->id, ['id' => $prm->id]) !!}</td>
            @endif
            <td>{!! link_to_route('permission.show', $prm->name, ['id' => $prm->id]) !!}</td>
            <td>{{ $prm->display_name }}</td>
            <td>{{ $prm->description  }}</td>
            <td>{{ $prm->created_at   }}</td>
            <td>{{ $prm->updated_at   }}</td>
        </tr>
    @endforeach
</table>

{!! isset($permission) ? $permissions->appends($permission)->render() : $permissions->render() !!}

<!-- End of pages/permission/list.blade.php -->

