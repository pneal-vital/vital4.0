<!-- Beginning of pages/userRoles/list.blade.php  -->

{{--
    * Table Structure
    * desc role_user;
    +---------+------------------+------+-----+---------+-------+
    | Field   | Type             | Null | Key | Default | Extra |
    +---------+------------------+------+-----+---------+-------+
    | user_id | int(10) unsigned | NO   | PRI | NULL    |       |
    | role_id | int(10) unsigned | NO   | PRI | NULL    |       |
    +---------+------------------+------+-----+---------+-------+
    2 rows in set (0.00 sec)
--}}

<table class="table">
    <tr>
        <th>{!! Lang::get('labels.name'   ) !!}</th>
        <th>{!! Lang::get('labels.role_id') !!}</th>
    </tr>

    @foreach($userRoles as $ur)
        <tr>
            <td>{!! link_to_route('user.show', $ur->user->name        , ['id' => $ur->user_id]) !!}</td>
            <td>{!! link_to_route('role.show', $ur->role->display_name, ['id' => $ur->role_id]) !!}</td>
        </tr>
    @endforeach
</table>

{!! isset($userRole) ? $userRoles->appends($userRole)->render() : $userRoles->render() !!}

<!-- End of pages/userRoles/list.blade.php -->

