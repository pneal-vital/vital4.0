<!-- Beginning of pages/inboundOrderDetail/list.blade.php  -->

{{--
    * Table Structure
    * desc Inbound_Order_Detail;
	+--------------+-------------+------+-----+---------+-------+
	| Field        | Type        | Null | Key | Default | Extra |
	+--------------+-------------+------+-----+---------+-------+
	| objectID     | bigint(20)  | NO   | PRI | NULL    |       |
	| Order_Number | varchar(85) | YES  | MUL | NULL    |       | contains InboundOrder.objectID
	| SKU          | varchar(85) | YES  | MUL | NULL    |       | contains Article.objectID
	| Expected_Qty | varchar(85) | YES  |     | NULL    |       |
	| Actual_Qty   | varchar(85) | YES  |     | NULL    |       |
	| Status       | varchar(85) | YES  | MUL | NULL    |       |
	| UPC          | varchar(35) | YES  | MUL | NULL    |       |
	| UOM          | varchar(85) | YES  |     |         |       |
	+--------------+-------------+------+-----+---------+-------+
--}}

<table class="table">
    <tr>
        <th>{!! Lang::get('labels.objectID')     !!}</th>
        @unless(isset($hideOrderNumber))
            <th>{!! Lang::get('labels.Order_Number') !!}</th>
        @endunless
        <th>{!! Lang::get('labels.SKU')          !!}</th>
        <th>{!! Lang::get('labels.Expected_Qty') !!}</th>
        <th>{!! Lang::get('labels.Actual_Qty')   !!}</th>
        <th>{!! Lang::get('labels.Status')       !!}</th>
        <th>{!! Lang::get('labels.UPC')          !!}</th>
        <th>{!! Lang::get('labels.UOM')          !!}</th>
    </tr>

    @foreach($inboundOrderDetails as $iod)
        <tr>
            <td>{!! link_to_route('inboundOrderDetail.show', $iod->objectID    , ['id' => $iod->objectID])     !!}</td>
            @unless(isset($hideOrderNumber))
                <td>{!! link_to_route('inboundOrder.show'  , $iod->Order_Number, ['id' => $iod->Order_Number]) !!}</td>
            @endunless
            <td>{!! link_to_route('article.show'           , $iod->SKU         , ['id' => $iod->SKU])          !!}</td>
            <td>{{ $iod->Expected_Qty }}</td>
            <td>{{ $iod->Actual_Qty   }}</td>
            <td>{{ Lang::get('lists.inboundOrderDetail.status.'.$iod->Status) }}</td>
            <td>{{ $iod->UPC          }}</td>
            <td>{{ $uoms[$iod->UOM]   }}</td>
        </tr>
    @endforeach
</table>

{!! isset($inboundOrderDetail) ? $inboundOrderDetails->appends($inboundOrderDetail)->render() : $inboundOrderDetails->render() !!}

<!-- End of pages/inboundOrderDetail/list.blade.php -->

