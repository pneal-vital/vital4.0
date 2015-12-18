@extends('pages.panelList')

@section('title')
    <!-- section('title') of pages/invSummary/show.blade.php  -->

    @lang('labels.titles.InventorySummary')

    <!-- stop of pages/invSummary/show.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/invSummary/show.blade.php  -->

    <h4 class="panel-title pull-left">
        @lang('labels.titles.InventorySummary_for') {{ $invSummary->Client_SKU }}
    </h4>

    <!-- stop of pages/invSummary/show.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/invSummary/show.blade.php  -->

    {{--
    * Table Structure
    * desc Inventory_Summary;
    +-------------+--------------+------+-----+---------------------+-------+
    | Field       | Type         | Null | Key | Default             | Extra |
    +-------------+--------------+------+-----+---------------------+-------+
    | objectID    | bigint(20)   | NO   | PRI | NULL                |       | <== UPC.objectID
    | Client_SKU  | varchar(85)  | YES  |     | NULL                |       | <== UPC.Client_SKU
    | Description | varchar(255) | YES  |     | NULL                |       |
    | pickQty     | int(11)      | NO   |     | 0                   |       |
    | actQty      | int(11)      | NO   |     | 0                   |       |
    | resQty      | int(11)      | NO   |     | 0                   |       |
    | replenPrty  | int(11)      | YES  |     | NULL                |       |
    | created_at  | timestamp    | NO   |     | 0000-00-00 00:00:00 |       |
    | updated_at  | timestamp    | NO   |     | 0000-00-00 00:00:00 |       |
    +-------------+--------------+------+-----+---------------------+-------+
    9 rows in set (0.03 sec)
    --}}

    @if(Entrust::hasRole(['support']))
        @include('fields.textList', ['fieldName' => 'objectID'   , 'fieldValue' => $invSummary->objectID    ])
    @endif
    @include('fields.textList', ['fieldName' => 'Client_SKU' , 'fieldValue' => $invSummary->Client_SKU  ])
    @include('fields.textList', ['fieldName' => 'Description', 'fieldValue' => $invSummary->Description ])
    @include('fields.textList', ['fieldName' => 'pickQty'    , 'fieldValue' => $invSummary->pickQty     ])
    @include('fields.textList', ['fieldName' => 'actQty'     , 'fieldValue' => $invSummary->actQty      ])
    @include('fields.textList', ['fieldName' => 'resQty'     , 'fieldValue' => $invSummary->resQty      ])
    @include('fields.textList', ['fieldName' => 'replenPrty' , 'fieldValue' => $invSummary->replenPrty  ])
    @include('fields.textList', ['fieldName' => 'created_at' , 'fieldValue' => ($invSummary->updated_at-> year > 2015 ? $invSummary->updated_at : $invSummary->created_at) ])

    <div class="col-sm-5">
        <a href="{!! route('invSummary.details', [$invSummary->objectID]) !!}">
            <button id="btn-details" name="btn_details" class="btn btn-primary" value="details">@lang('labels.buttons.inventoryDetails')</button>
        </a>
    </div>

    <!-- stop of pages/invSummary/show.blade.php, section('form') -->
@stop
