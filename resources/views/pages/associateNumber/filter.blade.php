<!-- Beginning of pages/associateNumber/filter.blade.php -->

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

@include('fields.dateEntry', ['fieldName' => 'fromDate', 'columnSize' => 'col-md-6', 'fieldFormat' => 'Y-m-d H:i', 'onChangeSubmit' => 'true' ])
@include('fields.dateEntry', ['fieldName' => 'toDate'  , 'columnSize' => 'col-md-6', 'fieldFormat' => 'Y-m-d H:i', 'onChangeSubmit' => 'true' ])

@include('fields.button', ['fieldSizeOffset' => 'col-md-10 col-md-offset-1', 'columnSize' => 'col-md-12'])

<!-- End of pages/associateNumber/filter.blade.php -->
