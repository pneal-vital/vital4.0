<!-- Beginning of pages/userConversation/form.blade.php -->

{{--
    * Table Structure
    * desc User_Conversation;
    +-------------+---------------------+------+-----+---------------------+----------------+
    | Field       | Type                | Null | Key | Default             | Extra          |
    +-------------+---------------------+------+-----+---------------------+----------------+
    | activityID  | bigint(20) unsigned | NO   | PRI | NULL                | auto_increment |
    | POD         | bigint(20)          | NO   |     | NULL                |                |
    | Article     | bigint(20)          | NO   |     | NULL                |                |
    | User_Name   | varchar(85)         | NO   |     | NULL                |                |
    | Sender_Name | varchar(85)         | NO   |     | NULL                |                |
    | created_at  | timestamp           | NO   |     | 0000-00-00 00:00:00 |                |
    | updated_at  | timestamp           | NO   |     | 0000-00-00 00:00:00 |                |
    | Text        | text                | NO   |     | NULL                |                |
    +-------------+---------------------+------+-----+---------------------+----------------+
    8 rows in set (0.01 sec)
--}}

@include('fields.textEntry', ['fieldName' => 'POD'        ])
@include('fields.textEntry', ['fieldName' => 'Article'    ])
@include('fields.textEntry', ['fieldName' => 'User_Name'  ])
@include('fields.textEntry', ['fieldName' => 'Sender_Name'])
@include('fields.textEntry', ['fieldName' => 'Text'       ])

@include('fields.button')

<!-- End of pages/userConversation/form.blade.php -->
