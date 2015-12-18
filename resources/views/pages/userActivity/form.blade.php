<!-- Beginning of pages/userActivity/form.blade.php -->

{{--
    * Table Structure
    * desc User_Activity;
    +------------+---------------------+------+-----+---------------------+----------------+
    | Field      | Type                | Null | Key | Default             | Extra          |
    +------------+---------------------+------+-----+---------------------+----------------+
    | activityID | bigint(20) unsigned | NO   | PRI | NULL                | auto_increment |
    | id         | bigint(20)          | NO   |     | NULL                |                |
    | classID    | varchar(85)         | NO   |     | NULL                |                |
    | User_Name  | varchar(85)         | NO   |     | NULL                |                |
    | created_at | timestamp           | NO   |     | 0000-00-00 00:00:00 |                |
    | updated_at | timestamp           | NO   |     | 0000-00-00 00:00:00 |                |
    | Purpose    | varchar(85)         | NO   |     | NULL                |                |
    +------------+---------------------+------+-----+---------------------+----------------+
    7 rows in set (0.00 sec)
--}}

@include('fields.textEntry', ['fieldName' => 'id'        ])
@include('fields.textEntry', ['fieldName' => 'classID'   ])
@include('fields.textEntry', ['fieldName' => 'User_Name' ])
@include('fields.textEntry', ['fieldName' => 'Purpose'   ])

@include('fields.button')

<!-- End of pages/userActivity/form.blade.php -->
