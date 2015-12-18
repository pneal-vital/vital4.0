<!-- Beginning of pages/receiptHistory/list.blade.php  -->

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

<table class="table">
    <tr>
        @unless(isset($hideActivityID))
            <th>{!! Lang::get('labels.activityID') !!}</th>
        @endunless
        <th>{!! Lang::get('labels.PO')         !!}</th>
        <th>{!! Lang::get('labels.POD')        !!}</th>
        <th>{!! Lang::get('labels.Article')    !!}</th>
        <th>{!! Lang::get('labels.UPC')        !!}</th>
        <th>{!! Lang::get('labels.Inventory')  !!}</th>
        <th>{!! Lang::get('labels.Tote')       !!}</th>
        <th>{!! Lang::get('labels.Cart')       !!}</th>
        <th>{!! Lang::get('labels.Location')   !!}</th>
        <th>{!! Lang::get('labels.User_Name')  !!}</th>
        <th>{!! Lang::get('labels.created_at') !!}</th>
        <th>{!! Lang::get('labels.updated_at') !!}</th>
    </tr>
    <tr>
        <th colspan="12">{!! Lang::get('labels.Activity')   !!}</th>
    </tr>

    @foreach($receiptHistories as $rh)
        <tr>
            @unless(isset($hideActivityID))
                <td>{!! link_to_route('receiptHistory.show', $rh->activityID, ['id' => $rh->activityID]) !!}</td>
            @endunless
            <td>{!! isset($rh->PO       ) ? link_to_route('po.show'       , $rh->PO       , ['id' => $rh->PO       ]) : '' !!}</td>
            <td>{!! isset($rh->POD      ) ? link_to_route('pod.show'      , $rh->POD      , ['id' => $rh->POD      ]) : '' !!}</td>
            <td>{!! isset($rh->Article  ) ? link_to_route('article.show'  , $rh->Article  , ['id' => $rh->Article  ]) : '' !!}</td>
            <td>{!! isset($rh->UPC      ) ? link_to_route('upc.show'      , $rh->UPC      , ['id' => $rh->UPC      ]) : '' !!}</td>
            <td>{!! isset($rh->Inventory) ? link_to_route('inventory.show', $rh->Inventory, ['id' => $rh->Inventory]) : '' !!}</td>
            <td>{!! isset($rh->Tote     ) ? link_to_route('tote.show'     , $rh->Tote     , ['id' => $rh->Tote     ]) : '' !!}</td>
            <td>{!! isset($rh->Cart     ) ? link_to_route('pallet.show'   , $rh->Cart     , ['id' => $rh->Cart     ]) : '' !!}</td>
            <td>{!! isset($rh->Location ) ? link_to_route('location.show' , $rh->Location , ['id' => $rh->Location ]) : '' !!}</td>
            <td>{{ $rh->User_Name  }}</td>
            <td>{{ $rh->created_at }}</td>
            <td>{{ $rh->updated_at }}</td>
        </tr>
        <tr>
            <td colspan="12">{{ $rh->Activity   }}</td>
        </tr>
    @endforeach
</table>

{!! isset($receiptHistory) ? $receiptHistories->appends($receiptHistory)->render() : $receiptHistories->render() !!}

<!-- End of pages/receiptHistory/list.blade.php -->

