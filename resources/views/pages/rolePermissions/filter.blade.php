<!-- Beginning of pages/rolePermissions/filter.blade.php -->

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

@include('fields.ddList', ['fieldName' => 'role_id'      , 'lists' => $roles      , 'onChangeSubmit' => 'true' ])
@include('fields.ddList', ['fieldName' => 'permission_id', 'lists' => $permissions, 'onChangeSubmit' => 'true' ])

@include('fields.button')

<!-- End of pages/rolePermissions/filter.blade.php -->
