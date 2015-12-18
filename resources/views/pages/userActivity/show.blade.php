@extends('pages.panel')

@section('panel')
    <!-- section('panel') of pages/userActivity/show.blade.php  -->

    <div class="panel-heading">@lang('labels.titles.UserActivities_for') {{ $userActivity->classID }} &amp; {{ $userActivity->User_Name }}</div>
    <div class="panel-body">

        @include('errors.list')

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

        @include('fields.textList', ['fieldName' => 'activityID', 'fieldValue' => $userActivity->activityID ])
        @include('fields.textList', ['fieldName' => 'id'        , 'fieldValue' => $userActivity->id         ])
        @include('fields.textList', ['fieldName' => 'classID'   , 'fieldValue' => $userActivity->classID    ])
        @include('fields.textList', ['fieldName' => 'User_Name' , 'fieldValue' => $userActivity->User_Name  ])
        @include('fields.textList', ['fieldName' => 'created_at', 'fieldValue' => $userActivity->created_at ])
        @include('fields.textList', ['fieldName' => 'updated_at', 'fieldValue' => $userActivity->updated_at ])
        @include('fields.textList', ['fieldName' => 'Purpose'   , 'fieldValue' => $userActivity->Purpose    ])

    </div>

    <!-- stop of pages/userActivity/show.blade.php, section('panel') -->
@stop

