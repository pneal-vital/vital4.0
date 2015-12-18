<!-- Beginning of pages/associateNumber/form.blade.php -->

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
    @include('fields.textEntry', ['fieldName' => 'recordID'])
@endif
@include('fields.textEntry', ['fieldName' => 'dateStamp'])
@include('fields.textEntry', ['fieldName' => 'userName'])
@include('fields.textEntry', ['fieldName' => 'receivedUnits'])
@include('fields.textEntry', ['fieldName' => 'putAwayRec'])
@include('fields.textEntry', ['fieldName' => 'putAwayRplComb'])
@include('fields.textEntry', ['fieldName' => 'putAwayRplSngl'])
@include('fields.textEntry', ['fieldName' => 'putAwayReserve'])
@include('fields.textEntry', ['fieldName' => 'replenTotes'])

@include('fields.button')

<!-- End of pages/associateNumber/form.blade.php -->
