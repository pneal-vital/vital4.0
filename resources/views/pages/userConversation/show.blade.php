@extends('pages.panel')

@section('panel')
    <!-- section('panel') of pages/userConversation/show.blade.php  -->

    <div class="panel-heading">@lang('labels.titles.UserConversations_for') {{ $userConversation->POD }}, {{ $userConversation->Article }} &amp; {{ $userConversation->User_Name }}</div>
    <div class="panel-body">

        @include('errors.list')

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

        @include('fields.textList', ['fieldName' => 'activityID' , 'fieldValue' => $userConversation->activityID ])
        @include('fields.textList', ['fieldName' => 'POD'        , 'fieldValue' => $userConversation->POD        ])
        @include('fields.textList', ['fieldName' => 'Article'    , 'fieldValue' => $userConversation->Article    ])
        @include('fields.textList', ['fieldName' => 'User_Name'  , 'fieldValue' => $userConversation->User_Name  ])
        @include('fields.textList', ['fieldName' => 'Sender_Name', 'fieldValue' => $userConversation->Sender_Name])
        @include('fields.textList', ['fieldName' => 'created_at' , 'fieldValue' => $userConversation->created_at ])
        @include('fields.textList', ['fieldName' => 'updated_at' , 'fieldValue' => $userConversation->updated_at ])
        @include('fields.textList', ['fieldName' => 'Text'       , 'fieldValue' => $userConversation->Text       ])

    </div>

    <!-- stop of pages/userConversation/show.blade.php, section('panel') -->
@stop

