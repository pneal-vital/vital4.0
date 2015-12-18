<!-- Beginning of pages/invSummary/list.blade.php  -->

{{--
    * Table Structure
    * desc Inventory_Summary;
    +-------------+--------------+------+-----+---------------------+-------+
    | Field       | Type         | Null | Key | Default             | Extra |
    +-------------+--------------+------+-----+---------------------+-------+
    | objectID    | bigint(20)   | NO   | PRI | NULL                |       |
    | Client_SKU  | varchar(85)  | YES  |     | NULL                |       |
    | Description | varchar(255) | YES  |     | NULL                |       |
    | pickQty     | int(10)      | NO   |     | NULL                |       |
    | actQty      | int(10)      | NO   |     | NULL                |       |
    | resQty      | int(10)      | NO   |     | NULL                |       |
    | replenPrty  | int(10)      | YES  |     | NULL                |       |
    | created_at  | timestamp    | NO   |     | 0000-00-00 00:00:00 |       |
    | updated_at  | timestamp    | NO   |     | 0000-00-00 00:00:00 |       |
    +-------------+--------------+------+-----+---------------------+-------+
    9 rows in set (0.03 sec)
    --}}

<table class="table">
    <tr>
        @if(Entrust::hasRole(['support']))
            <th>{!! Lang::get('labels.objectID') !!}</th>
        @endif
        <th>{!! Lang::get('labels.Client_SKU')  !!}</th>
        <th>{!! Lang::get('labels.Description') !!}</th>
        <th>{!! Lang::get('labels.pickQty')     !!}</th>
        <th>{!! Lang::get('labels.actQty')      !!}</th>
        <th>{!! Lang::get('labels.resQty')      !!}</th>
        <th>{!! Lang::get('labels.replenPrty')  !!}</th>
        <th>{!! Lang::get('labels.created_at')  !!}</th>
    </tr>

    @foreach($invSummaries as $inv)
        <tr>
            @if(Entrust::hasRole(['support']))
                <td>{!! link_to_route('invSummary.show', $inv->objectID, ['id' => $inv->objectID]) !!}</td>
            @endif
            <td>{!! link_to_route('invSummary.show', $inv->Client_SKU, ['id' => $inv->objectID]) !!}</td>
            <td>{{ $inv->Description }}</td>
            <td>{{ $inv->pickQty     }}</td>
            <td>{{ $inv->actQty      }}</td>
            <td>{{ $inv->resQty      }}</td>
            <td>{{ $inv->replenPrty  }}</td>
            <td>{{ ($inv->updated_at-> year > 2015 ? $inv->updated_at : $inv->created_at) }}</td>
        </tr>
    @endforeach
</table>

@if(count($invSummaries))
    {!! isset($invSummary) ? $invSummaries->appends($invSummary)->render() : $invSummaries->render() !!}
@endif

<!-- End of pages/invSummary/list.blade.php -->
