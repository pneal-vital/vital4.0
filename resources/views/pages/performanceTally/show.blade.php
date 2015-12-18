@extends('pages.panelList')

@section('title')
    <!-- section('title') of pages/performanceTally/show.blade.php  -->

    @lang('labels.titles.PerformanceTally')

    <!-- stop of pages/performanceTally/show.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/performanceTally/show.blade.php  -->

    @lang('labels.titles.PerformanceTally_for') {{ $performanceTally->dateStamp }}

    <!-- stop of pages/performanceTally/show.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/performanceTally/show.blade.php  -->
    
    {{--
    * Performance_Tally;
    +----------------+---------------------+------+-----+---------------------+----------------+
    | Field          | Type                | Null | Key | Default             | Extra          |
    +----------------+---------------------+------+-----+---------------------+----------------+
    | recordID       | bigint(20) unsigned | NO   | PRI | NULL                | auto_increment |
    | dateStamp      | timestamp           | NO   |     | 0000-00-00 00:00:00 |                |
    | userName       | varchar(45)         | NO   |     | NULL                |                |
    | receivedUnits  | int(11)             | NO   |     | NULL                |                | <== populated by ArticleFlow.putUPCsIntoTote(..)
    | putAwayRec     | int(11)             | NO   |     | NULL                |                | <== populated by gunApp3.putToteIntoLocation(tote,loc)
    | putAwayRplComb | int(11)             | NO   |     | NULL                |                | <== populated by gunApp3.scanUPCsIntoTote(tote,loc)
    | putAwayRplSngl | int(11)             | NO   |     | NULL                |                | <== populated by gunApp3.putToteIntoLocation(tote,loc)
    | putAwayReserve | int(11)             | NO   |     | NULL                |                | <== populated by gunApp3.putToteIntoLocation(tote,loc)
    | replenTotes    | int(11)             | NO   |     | NULL                |                | <== populated by gunApp3.takeReplenJob()
    +----------------+---------------------+------+-----+---------------------+----------------+
    --}}

    @if(Entrust::hasRole(['support']))
        @include('fields.textList', ['fieldName' => 'recordID'  , 'fieldValue' => $performanceTally->recordID       ])
    @endif
    @include('fields.textList', ['fieldName' => 'dateStamp'     , 'fieldValue' => $performanceTally->dateStamp      ])
    @include('fields.textList', ['fieldName' => 'userName'      , 'fieldValue' => $performanceTally->userName       ])
    @include('fields.textList', ['fieldName' => 'receivedUnits' , 'fieldValue' => $performanceTally->receivedUnits  ])
    @include('fields.textList', ['fieldName' => 'putAwayRec'    , 'fieldValue' => $performanceTally->putAwayRec     ])
    @include('fields.textList', ['fieldName' => 'putAwayRplComb', 'fieldValue' => $performanceTally->putAwayRplComb ])
    @include('fields.textList', ['fieldName' => 'putAwayRplSngl', 'fieldValue' => $performanceTally->putAwayRplSngl ])
    @include('fields.textList', ['fieldName' => 'putAwayReserve', 'fieldValue' => $performanceTally->putAwayReserve ])
    @include('fields.textList', ['fieldName' => 'replenTotes'   , 'fieldValue' => $performanceTally->replenTotes    ])

    <!-- stop of pages/performanceTally/show.blade.php, section('form') -->
@stop

