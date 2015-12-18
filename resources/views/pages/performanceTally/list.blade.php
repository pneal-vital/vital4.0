<!-- Beginning of pages/performanceTally/list.blade.php  -->

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
        @if(Entrust::hasRole(['support']))
            <th>{!! Lang::get('labels.recordID') !!}</th>
        @endif
        <th>{!! Lang::get('labels.dateStamp')      !!}</th>
        <th>{!! Lang::get('labels.userName')       !!}</th>
        <th>{!! Lang::get('labels.receivedUnits')  !!}</th>
        <th>{!! Lang::get('labels.putAwayRec')     !!}</th>
        <th>{!! Lang::get('labels.putAwayRplComb') !!}</th>
        <th>{!! Lang::get('labels.putAwayRplSngl') !!}</th>
        <th>{!! Lang::get('labels.putAwayReserve') !!}</th>
        <th>{!! Lang::get('labels.replenTotes')    !!}</th>
    </tr>

    @foreach($performanceTallies as $pt)
        <tr>
            @if(Entrust::hasRole(['support']))
                <td>{!! link_to_route((isset($route) ? $route : 'performanceTally.show'), $pt->recordID, ['id' => $pt->recordID]) !!}</td>
            @endif
            <td>{!! link_to_route((isset($route) ? $route : 'performanceTally.show'), $pt->dateStamp, ['id' => $pt->recordID]) !!}</td>
            <td>{{ $pt->userName       }}</td>
            <td>{{ $pt->receivedUnits  }}</td>
            <td>{{ $pt->putAwayRec     }}</td>
            <td>{{ $pt->putAwayRplComb }}</td>
            <td>{{ $pt->putAwayRplSngl }}</td>
            <td>{{ $pt->putAwayReserve }}</td>
            <td>{{ $pt->replenTotes    }}</td>
        </tr>
    @endforeach
</table>

{!! isset($performanceTally) ? $performanceTallies->appends($performanceTally)->render() : $performanceTallies->render() !!}

<!-- End of pages/performanceTally/list.blade.php -->

