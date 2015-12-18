@extends('pages.panel')

@section('panel')
    <!-- section('panel') of pages/receiptHistory/show.blade.php  -->

    <div class="panel-heading">@lang('labels.titles.ReceiptHistories_for') {{ $receiptHistory->activityID }} &amp; {{ $receiptHistory->User_Name }}</div>
    <div class="panel-body">

        @include('errors.list')

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

        @include('fields.textList', ['fieldName' => 'activityID', 'fieldValue' => $receiptHistory->activityID ])
        @include('fields.textList', ['fieldName' => 'PO'        , 'fieldValue' => isset($receiptHistory->PO        ) ? $receiptHistory->PO        : '', 'urlName' => 'po.show'        ])
        @include('fields.textList', ['fieldName' => 'POD'       , 'fieldValue' => isset($receiptHistory->POD       ) ? $receiptHistory->POD       : '', 'urlName' => 'pod.show'       ])
        @include('fields.textList', ['fieldName' => 'Article'   , 'fieldValue' => isset($receiptHistory->Article   ) ? $receiptHistory->Article   : '', 'urlName' => 'article.show'   ])
        @include('fields.textList', ['fieldName' => 'UPC'       , 'fieldValue' => isset($receiptHistory->UPC       ) ? $receiptHistory->UPC       : '', 'urlName' => 'upc.show'       ])
        @include('fields.textList', ['fieldName' => 'Inventory' , 'fieldValue' => isset($receiptHistory->Inventory ) ? $receiptHistory->Inventory : '', 'urlName' => 'inventory.show' ])
        @include('fields.textList', ['fieldName' => 'Tote'      , 'fieldValue' => isset($receiptHistory->Tote      ) ? $receiptHistory->Tote      : '', 'urlName' => 'tote.show'      ])
        @include('fields.textList', ['fieldName' => 'Cart'      , 'fieldValue' => isset($receiptHistory->Cart      ) ? $receiptHistory->Cart      : '', 'urlName' => 'pallet.show'    ])
        @include('fields.textList', ['fieldName' => 'Location'  , 'fieldValue' => isset($receiptHistory->Location  ) ? $receiptHistory->Location  : '', 'urlName' => 'location.show'  ])
        @include('fields.textList', ['fieldName' => 'User_Name' , 'fieldValue' => $receiptHistory->User_Name  ])
        @include('fields.textList', ['fieldName' => 'created_at', 'fieldValue' => $receiptHistory->created_at ])
        @include('fields.textList', ['fieldName' => 'updated_at', 'fieldValue' => $receiptHistory->updated_at ])
        @include('fields.textList', ['fieldName' => 'Activity'  , 'fieldValue' => $receiptHistory->Activity   ])

    </div>

    <!-- stop of pages/receiptHistory/show.blade.php, section('panel') -->
@stop

