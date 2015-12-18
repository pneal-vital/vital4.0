<!-- Beginning of pages/purchaseOrderDetail/list.blade.php  -->

{{--
    * PurchaseOrderDetail
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

    @foreach($purchaseOrderDetails as $pod)
        <tr>
            <td>{!! link_to_route('purchaseOrderDetail.show', $pod->objectID    , ['id' => $pod->objectID])     !!}</td>
            @unless(isset($hideOrderNumber))
                <td>{!! link_to_route('inboundOrder.show'   , $pod->Order_Number, ['id' => $pod->Order_Number]) !!}</td>
            @endunless
            <td>{!! link_to_route('article.show'            , $pod->SKU         , ['id' => $pod->SKU])          !!}</td>
            <td>{{ $pod->Expected_Qty }}</td>
            <td>{{ $pod->Actual_Qty   }}</td>
            <td>{{ $pod->Status       }}</td>
            <td>{{ $pod->UPC          }}</td>
            <td>{{ $uoms[$pod->UOM]   }}</td>
        </tr>
    @endforeach
</table>

{!! isset($purchaseOrderDetail) ? $purchaseOrderDetails->appends($purchaseOrderDetail)->render() : $purchaseOrderDetails->render() !!}

<!-- End of pages/purchaseOrderDetail/list.blade.php -->

