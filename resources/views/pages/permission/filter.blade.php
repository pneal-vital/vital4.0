<!-- Beginning of pages/permission/filter.blade.php -->

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

@include('fields.textEntry', ['fieldName' => 'name'         ])
@include('fields.textEntry', ['fieldName' => 'display_name' ])
@include('fields.textEntry', ['fieldName' => 'description'  ])

@include('fields.button')

<!-- End of pages/permission/filter.blade.php -->
