<!-- Beginning of pages/user/list.blade.php  -->

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

<table class="table">
    <tr>
        @if(Entrust::hasRole(['support']))
            <th>{!! Lang::get('labels.id')         !!}</th>
        @endif
        <th>{!! Lang::get('labels.name')       !!}</th>
        <th>{!! Lang::get('labels.email')      !!}</th>
        <th>{!! Lang::get('labels.created_at') !!}</th>
        <th>{!! Lang::get('labels.updated_at') !!}</th>
        @include('fields.cedIcons', ['model' => 'user', 'elemType' => 'th'])
    </tr>

    @foreach($users as $usr)
        <tr>
            @if(Entrust::hasRole(['support']))
                <td>{!! link_to_route('user.show', $usr->id, ['id' => $usr->id]) !!}</td>
            @endif
            <td>{!! link_to_route('user.show', $usr->name, ['id' => $usr->id]) !!}</td>
            <td>{{ $usr->email      }}</td>
            <td>{{ $usr->created_at }}</td>
            @if($usr->updated_at > '0000-00-00')
                <td>{{ $usr->updated_at }}</td>
            @else
                <td> </td>
            @endif
            @include('fields.cedIcons', ['model' => 'user', 'elemType' => 'td', 'id' => $usr->id])
        </tr>
    @endforeach
</table>

{!! isset($user) ? $users->appends($user)->render() : $users->render() !!}

@include('fields.cedIcons', ['model' => 'role', 'elemType' => 'script'])

<!-- End of pages/user/list.blade.php -->

