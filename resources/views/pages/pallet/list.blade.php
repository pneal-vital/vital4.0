<!-- Beginning of pages/pallet/list.blade.php  -->

{{--
    * desc Pallet;
    +-----------+-------------+------+-----+---------+-------+
    | Field     | Type        | Null | Key | Default | Extra |
    +-----------+-------------+------+-----+---------+-------+
    | objectID  | bigint(20)  | NO   | PRI | 0       |       |
    | Pallet_ID | varchar(85) | NO   | MUL |         |       | contains names like INBOUND, or => Generic_Container, Inventory, Item, Label_Printer, Outbound_Order_Detail, Pallet, Pick, Pick_Detail, Shipment
    | x         | varchar(85) | NO   |     |         |       |
    | y         | varchar(85) | NO   |     |         |       |
    | z         | varchar(85) | NO   |     |         |       |
    | Status    | varchar(85) | NO   |     |         |       | in ('LOCK', 'OPEN', 'LOADED', 'SHIPPED')
    +-----------+-------------+------+-----+---------+-------+
--}}

<table class="table table-bordered">
    <tr>
        @if(Entrust::hasRole(['support']))
            <th>{!! Lang::get('labels.objectID')  !!}</th>
        @endif
        <th>{!! Lang::get('labels.Pallet_ID') !!}</th>
        @if(Entrust::hasRole(['support']))
            <th>{!! Lang::get('labels.x')         !!}</th>
            <th>{!! Lang::get('labels.y')         !!}</th>
            <th>{!! Lang::get('labels.z')         !!}</th>
        @endif
        <th>{!! Lang::get('labels.Status')    !!}</th>
        @include('fields.cedIcons', ['model' => 'pallet', 'elemType' => 'th'])
    </tr>

    <!-- gcID: {{ is_array($ids = (isset($gcID) ? ['gcID' => $gcID] : [])) }} -->
    <!-- routeName: {{ $routeName = (isset($routeName) ? $routeName : 'pallet.show') }} -->
    @foreach($pallets as $plt)
        <tr>
            @if(Entrust::hasRole(['support']))
                <td>{!! link_to_route((isset($route) ? $route : $routeName), $plt->objectID, ['id' => $plt->objectID]+$ids) !!}</td>
            @endif
            <td>{!! link_to_route((isset($route) ? $route : $routeName), $plt->Pallet_ID, ['id' => $plt->objectID]+$ids) !!}</td>
            @if(Entrust::hasRole(['support']))
                <td>{{ $plt->x         }}</td>
                <td>{{ $plt->y         }}</td>
                <td>{{ $plt->z         }}</td>
            @endif
            <td>{{ Lang::get('lists.pallet.status.'.$plt->Status) }}</td>
            @include('fields.cedIcons', ['model' => 'pallet', 'elemType' => 'td', 'id' => $plt->objectID])
        </tr>
    @endforeach
</table>

{!! isset($pallet) ? $pallets->appends($pallet)->render() : $pallets->render() !!}

@include('fields.cedIcons', ['model' => 'pallet', 'elemType' => 'script'])

<!-- End of pages/pallet/list.blade.php -->

