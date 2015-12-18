<!-- Beginning of pages/purchaseOrder/list.blade.php  -->

    {{--
    * PurchaseOrder;
    +----------------------+-------------+------+-----+---------+-------+
    | Field                | Type        | Null | Key | Default | Extra |
    +----------------------+-------------+------+-----+---------+-------+
    | Purchase_Order       | varchar(85) | YES  | MUL | NULL    |       |
    | objectID             | bigint(20)  | NO   | PRI | NULL    |       |
    | Order_Number         | varchar(85) | YES  |     | NULL    |       |
    | Client               | varchar(85) | YES  |     | NULL    |       |
    | Invoice_Number       | varchar(85) | YES  |     | NULL    |       |
    | Status               | varchar(85) | YES  | MUL | NULL    |       |
    | Created              | varchar(85) | YES  |     | NULL    |       |
    | Expected             | varchar(85) | YES  |     | NULL    |       |
    +----------------------+-------------+------+-----+---------+-------+
    --}}

<table class="table table-bordered">
    <tr>
        <th>{!! Lang::get('labels.Purchase_Order') !!}</th>
        @if(Entrust::hasRole(['support']))
            @if(isset($clients))
                <th>{!! Lang::get('labels.Client')     !!}</th>
            @endif
            <th>{!! Lang::get('labels.Order_Number')   !!}</th>
            <th>{!! Lang::get('labels.Invoice_Number') !!}</th>
        @endif
        <th>{!! Lang::get('labels.Status')         !!}</th>
        @if(Entrust::hasRole(['support']))
            <th>{!! Lang::get('labels.Created')        !!}</th>
        @endif
        <th>{!! Lang::get('labels.Expected')       !!}</th>
    </tr>

    @foreach($purchaseOrders as $po)

        <tr>
            <td>{!! link_to_route((isset($route) ? $route : 'po.show'), $po->Purchase_Order, ['id' => $po->Purchase_Order]) !!}</td>
            @if(Entrust::hasRole(['support']))
                @if(isset($clients))
                    <td>{{ $clients[$po->Client] }}</td>
                @endif
                <td>{{ $po->Order_Number   }}</td>
                <td>{{ $po->Invoice_Number }}</td>
            @endif
            <td>{{ Lang::get('lists.purchaseOrder.status.'.$po->Status) }}</td>
            @if(Entrust::hasRole(['support']))
                <td>{{ $po->Created        }}</td>
            @endif
            <td>{{ $po->Expected       }}</td>
        </tr>

    @endforeach

</table>

{!! isset($purchaseOrder) ? $purchaseOrders->appends($purchaseOrder)->render() : $purchaseOrders->render() !!}

<!-- End of pages/purchaseOrder/list.blade.php -->
