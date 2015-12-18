<!-- Beginning of pages/receiptHistory/filter.blade.php -->

{{--
    * Table Structure
    * desc Receipt_History;
    +------------+---------------------+------+-----+---------------------+----------------+
    | Field      | Type                | Null | Key | Default             | Extra          |
    +------------+---------------------+------+-----+---------------------+----------------+
    | activityID | bigint(20) unsigned | NO   | PRI | NULL                | auto_increment |
    | PO         | bigint(20)          | NO   |     | NULL                |                |
    | POD        | bigint(20)          | YES  |     | NULL                |                |
    | Article    | bigint(20)          | YES  |     | NULL                |                |
    | UPC        | bigint(20)          | YES  |     | NULL                |                |
    | Inventory  | bigint(20)          | YES  |     | NULL                |                |
    | Tote       | bigint(20)          | YES  |     | NULL                |                |
    | Cart       | bigint(20)          | YES  |     | NULL                |                |
    | Location   | bigint(20)          | YES  |     | NULL                |                |
    | User_Name  | varchar(85)         | NO   |     | NULL                |                |
    | created_at | timestamp           | NO   |     | 0000-00-00 00:00:00 |                |
    | updated_at | timestamp           | NO   |     | 0000-00-00 00:00:00 |                |
    | Activity   | text                | NO   |     | NULL                |                |
    +------------+---------------------+------+-----+---------------------+----------------+
    13 rows in set (0.00 sec)
--}}

@include('fields.textEntry', ['fieldName' => 'PO'         ])
@include('fields.textEntry', ['fieldName' => 'POD'        ])
@include('fields.textEntry', ['fieldName' => 'Article'    ])
@include('fields.textEntry', ['fieldName' => 'UPC'        ])
@include('fields.textEntry', ['fieldName' => 'Inventory'  ])
@include('fields.textEntry', ['fieldName' => 'Tote'       ])
@include('fields.textEntry', ['fieldName' => 'Cart'       ])
@include('fields.textEntry', ['fieldName' => 'Location'   ])
@include('fields.textEntry', ['fieldName' => 'User_Name'  ])
@include('fields.dateEntry', ['fieldName' => 'created_at', 'fieldFormat' => 'Y-m-d H:i', 'validateOnBlur' => 'false', 'onChangeSubmit' => 'true' ])

@include('fields.button')

<!-- End of pages/receiptHistory/filter.blade.php -->
