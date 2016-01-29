<!-- Beginning of pages/rolePermissions/list.blade.php  -->

{{--
    * Table Structure
    * desc permission_role;
    +---------------+------------------+------+-----+---------+-------+
    | Field         | Type             | Null | Key | Default | Extra |
    +---------------+------------------+------+-----+---------+-------+
    | permission_id | int(10) unsigned | NO   | PRI | NULL    |       |
    | role_id       | int(10) unsigned | NO   | PRI | NULL    |       |
    +---------------+------------------+------+-----+---------+-------+
    2 rows in set (0.02 sec)
--}}

<table class="table">
    <tr>
        <th>{!! Lang::get('labels.role_id'      ) !!}</th>
        <th>{!! Lang::get('labels.permission_id') !!}</th>
    </tr>

    @foreach($rolePermissions as $rp)
        <tr>
            <td>{!! link_to_route('role.show'      , $rp->role->display_name      , ['id' => $rp->role_id      ]) !!}</td>
            <td>{!! link_to_route('permission.show', $rp->permission->display_name, ['id' => $rp->permission_id]) !!}</td>
        </tr>
    @endforeach
</table>

<!-- End of pages/rolePermissions/list.blade.php -->

