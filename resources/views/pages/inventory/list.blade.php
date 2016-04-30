<!-- Beginning of pages/inventory/list.blade.php  -->

{{--
    * Table Structure
    * desc Inventory;
    +------------+-------------+------+-----+---------+-------+
    | Field      | Type        | Null | Key | Default | Extra |
    +------------+-------------+------+-----+---------+-------+
    | objectID   | bigint(20)  | NO   | PRI | NULL    |       |
    | Item       | varchar(85) | YES  | MUL | NULL    |       | => Item.objectID
    | Quantity   | varchar(85) | YES  |     | NULL    |       |
    | Created    | varchar(85) | YES  |     | NULL    |       |
    | Status     | varchar(85) | YES  | MUL | NULL    |       |
    | Order_Line | varchar(85) | YES  | MUL | NULL    |       | => Inbound_Order | Outbound_Order.objectID
    | UOM        | varchar(85) | YES  |     |         |       | => UOM.objectID
    +------------+-------------+------+-----+---------+-------+
    7 rows in set (0.01 sec)
    --}}

<table class="table">
    <tr>
        @if(Entrust::hasRole(['support']))
            <th>{!! Lang::get('labels.objectID')  !!}</th>
        @else
            <th>{!! Lang::get('labels.Inventory') !!}</th>
        @endif
        <th>{!! Lang::get('labels.Item')       !!}</th>
        <th>{!! Lang::get('labels.Quantity')   !!}</th>
        <th>{!! Lang::get('labels.Created')    !!}</th>
        <th>{!! Lang::get('labels.Status')     !!}</th>
        <th>{!! Lang::get('labels.Order_Line') !!}</th>
        <th>{!! Lang::get('labels.UOM')        !!}</th>
        @include('fields.cedIcons', ['model' => 'inventory', 'elemType' => 'th'])
    </tr>

    @foreach($inventories as $inv)
        <tr>
            @if(Entrust::hasRole(['support']))
                <td>{!! link_to_route('inventory.show', $inv->objectID, ['id' => $inv->objectID]) !!}</td>
            @else
                <td>{!! link_to_route('inventory.show', Lang::get('labels.buttons.Show_Inventory'), ['id' => $inv->objectID]) !!}</td>
            @endif
            @if(isset($inv->Item_type))
                <td>({{ $inv->Item_type }})
                    {!! link_to_route(\Config::get("constants.routeName.$inv->Item_type.show"), $inv->Item_typeID, ['id' => $inv->Item]) !!}
                </td>
            @else
                <td>{{ $inv->Item       }}</td>
            @endif
            <td>{{ $inv->Quantity   }}</td>
            <td>{{ $inv->Created    }}</td>
            <td>{{ Lang::get('lists.inventory.status.'.$inv->Status) }}</td>
            @if(isset($inv->Order_Line_type))
                <td>({{ Lang::get('labels.'.$inv->Order_Line_type) }})
                    @if($inv->Order_Line_type == 'OutboundOrderDetail')
                        {{ $inv->Order_Line }}
                    @else
                        {!! link_to_route(\Config::get("constants.routeName.$inv->Order_Line_type.show"), $inv->Order_Line_typeID, ['id' => $inv->Order_Line]) !!}
                    @endif
                </td>
            @else
                <td>{{ $inv->Order_Line }}</td>
            @endif
            <td>{{ isset($inv->UOM) && isset($uoms[$inv->UOM]) ? $uoms[$inv->UOM] : $inv->UOM }}</td>
            @include('fields.cedIcons', ['model' => 'inventory', 'elemType' => 'td', 'id' => $inv->objectID])
        </tr>
    @endforeach
</table>

{!! isset($inventory) ? $inventories->appends($inventory)->render() : $inventories->render() !!}

@include('fields.cedIcons', ['model' => 'inventory', 'elemType' => 'script'])

<!-- End of pages/inventory/list.blade.php -->

