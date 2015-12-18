<!-- Beginning of pages/user/filter.blade.php -->

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

@include('fields.textEntry', ['fieldName' => 'name'  ])
@include('fields.textEntry', ['fieldName' => 'email' ])

@include('fields.button')

<!-- End of pages/user/filter.blade.php -->
