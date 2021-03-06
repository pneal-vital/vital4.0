<!-- Beginning of pages/userRoles/form.blade.php -->

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

@include('fields.textEntry', ['fieldName' => 'user_id' ])
@include('fields.ddList'   , ['fieldName' => 'role_id', 'lists' => $roles, 'onChangeSubmit' => 'true' ])

@include('fields.button')

<!-- End of pages/userRoles/form.blade.php -->
