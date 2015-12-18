@extends('pages.panelList')

@section('title')
    <!-- section('title') of pages/associateNumber/show.blade.php  -->

    @if($associateNumber->userName == Auth::user()->name)
        @lang('labels.titles.MyNumbers')
    @else
        @lang('labels.titles.AssociateNumber')
    @endif

    <!-- stop of pages/associateNumber/show.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/associateNumber/show.blade.php  -->

    @lang('labels.titles.AssociateNumber_for') {{ $associateNumber->userName }}

    <!-- stop of pages/associateNumber/show.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/associateNumber/show.blade.php  -->
    
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
        @if(isset($associateNumber->recordID))
            @include('fields.textList', ['fieldName' => 'recordID'  , 'fieldValue' => $associateNumber->recordID       ])
        @endif
    @endif
    @if(isset($associateNumber->dateStamp))
        @include('fields.textList', ['fieldName' => 'dateStamp'     , 'fieldValue' => $associateNumber->dateStamp      ])
    @else
        @if(isset($associateNumber->fromDate))
            @include('fields.textList', ['fieldName' => 'fromDate'     , 'fieldValue' => $associateNumber->fromDate      ])
        @endif
        @if(isset($associateNumber->toDate))
            @include('fields.textList', ['fieldName' => 'toDate'       , 'fieldValue' => $associateNumber->toDate        ])
        @endif
    @endif
    @include('fields.textList', ['fieldName' => 'userName'      , 'fieldValue' => $associateNumber->userName       ])
    @include('fields.textList', ['fieldName' => 'receivedUnits' , 'fieldValue' => $associateNumber->receivedUnits  ])
    @include('fields.textList', ['fieldName' => 'putAwayRec'    , 'fieldValue' => $associateNumber->putAwayRec     ])
    @include('fields.textList', ['fieldName' => 'putAwayRplComb', 'fieldValue' => $associateNumber->putAwayRplComb ])
    @include('fields.textList', ['fieldName' => 'putAwayRplSngl', 'fieldValue' => $associateNumber->putAwayRplSngl ])
    @include('fields.textList', ['fieldName' => 'putAwayReserve', 'fieldValue' => $associateNumber->putAwayReserve ])
    @include('fields.textList', ['fieldName' => 'replenTotes'   , 'fieldValue' => $associateNumber->replenTotes    ])

    <!-- stop of pages/associateNumber/show.blade.php, section('form') -->
@stop

