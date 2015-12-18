<!-- Beginning of pages/inboundOrder/list.blade.php  -->

{{--
    * Table Structure
    * desc Inbound_Order;
    +----------------------+-------------+------+-----+---------+-------+
    | Field                | Type        | Null | Key | Default | Extra |
    +----------------------+-------------+------+-----+---------+-------+
    | objectID             | bigint(20)  | NO   | PRI | NULL    |       |
    | Order_Number         | varchar(85) | YES  |     | NULL    |       |
    | Client               | varchar(85) | YES  |     | NULL    |       |
    | Purchase_Order       | varchar(85) | YES  | MUL | NULL    |       | contains PurchaseOrder.Purchase_Order
    | Invoice_Number       | varchar(85) | YES  |     | NULL    |       |
    | Status               | varchar(85) | YES  | MUL | NULL    |       |
    | Created              | varchar(85) | YES  |     | NULL    |       |
    | Expected             | varchar(85) | YES  |     | NULL    |       |
    +----------------------+-------------+------+-----+---------+-------+
    --}}

<table class="table">
    <tr>
        <th>{!! Lang::get('labels.objectID')        !!}</th>
        <th>{!! Lang::get('labels.Order_Number')    !!}</th>
        <th>{!! Lang::get('labels.Client')          !!}</th>
        <th>{!! Lang::get('labels.Purchase_Order')  !!}</th>
        <th>{!! Lang::get('labels.Invoice_Number')  !!}</th>
        <th>{!! Lang::get('labels.Status')          !!}</th>
        <th>{!! Lang::get('labels.Created')         !!}</th>
        <th>{!! Lang::get('labels.Expected')        !!}</th>
    </tr>

    @foreach($inboundOrders as $io)
        <tr>
            <td>{!! link_to_route('inboundOrder.show', $io->objectID, ['id' => $io->objectID]) !!}</td>
            <td>{{ $io->Order_Number     }}</td>
            <td>{{ $clients[$io->Client] }}</td>
            <td>{!! link_to_route('po.show', $io->Purchase_Order, ['id' => $io->Purchase_Order]) !!}</td>
            <td>{{ $io->Invoice_Number   }}</td>
            <td>{{ Lang::get('lists.inboundOrder.status.'.$io->Status) }}</td>
            <td>{{ $io->Created          }}</td>
            <td>{{ $io->Expected         }}</td>
        </tr>
    @endforeach
</table>

{!! isset($inboundOrder) ? $inboundOrders->appends($inboundOrder)->render() : $inboundOrders->render() !!}

<!-- End of pages/inboundOrder/list.blade.php -->

