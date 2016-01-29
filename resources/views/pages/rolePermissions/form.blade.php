<!-- Beginning of pages/rolePermissions/form.blade.php -->

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

@include('fields.ddList'   , ['fieldName' => 'role_id', 'lists' => $roles, 'onChangeSubmit' => 'true' ])
@include('fields.textEntry', ['fieldName' => 'permission_id' ])

@include('fields.button')

<!-- End of pages/rolePermissions/form.blade.php -->
