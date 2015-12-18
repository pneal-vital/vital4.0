<!-- Beginning of pages/userConversation/filter.blade.php -->

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

{{-- _if(Entrust::hasRole(['receiptSuper','receiptManager','support'])) --}}
@if(Entrust::hasRole(['teamLead','super','manager','support']))
    @include('fields.textEntry', ['fieldName' => 'User_Name'])
@else
    <div class="form-group">
        <label for="User_Name" class="col-md-4 control-label">@lang('labels.User_Name')</label>
        <div class="col-md-8">
            <div class="form-control mark">
                {{ $userConversation['User_Name'] }}
            </div>
        </div>
    </div>
@endif

@include('fields.textEntry', ['fieldName' => 'Sender_Name'])
@include('fields.dateEntry', ['fieldName' => 'created_at', 'fieldFormat' => 'Y-m-d H:i', 'validateOnBlur' => 'false', 'onChangeSubmit' => 'true' ])
@include('fields.dateEntry', ['fieldName' => 'updated_at', 'fieldFormat' => 'Y-m-d H:i', 'validateOnBlur' => 'false', 'onChangeSubmit' => 'true' ])
@include('fields.textEntry', ['fieldName' => 'Text'       ])

@include('fields.button')

<!-- End of pages/userConversation/filter.blade.php -->
