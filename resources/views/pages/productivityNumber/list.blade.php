<!-- Beginning of pages/productivityNumber/list.blade.php  -->

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

<table class="table table-bordered">
    <tr>
        <th>{!! Lang::get('labels.dateStamp')      !!}</th>
        <th>{!! Lang::get('labels.receivedUnits')  !!}</th>
        <th>{!! Lang::get('labels.putAwayRec')     !!}</th>
        <th>{!! Lang::get('labels.putAwayRplComb') !!}</th>
        <th>{!! Lang::get('labels.putAwayRplSngl') !!}</th>
        <th>{!! Lang::get('labels.putAwayReserve') !!}</th>
        <th>{!! Lang::get('labels.replenTotes')    !!}</th>
    </tr>

    @foreach($productivityNumbers as $pt)
        <tr>
            <!-- Should produce a link like:
              http://localhost:8888/associateNumber?_method=INDEX&fromDate=2015-08-05%2000:00&toDate=2015-08-14%2000
              -->
            <td>{!! link_to_route((isset($route) ? $route : 'associateNumber.index'), str_replace(' ', '&nbsp;', $pt->dateStamp), ['fromDate' => $pt->dateStamp, 'toDate' => str_replace(':00', ':59', $pt->dateStamp)]) !!}</td>
            <td>{{ $pt->receivedUnits  }}</td>
            <td>{{ $pt->putAwayRec     }}</td>
            <td>{{ $pt->putAwayRplComb }}</td>
            <td>{{ $pt->putAwayRplSngl }}</td>
            <td>{{ $pt->putAwayReserve }}</td>
            <td>{{ $pt->replenTotes    }}</td>
        </tr>
    @endforeach
</table>

{!! isset($productivityNumber) ? $productivityNumbers->appends($productivityNumber)->render() : $productivityNumbers->render() !!}

<!-- End of pages/productivityNumber/list.blade.php -->

