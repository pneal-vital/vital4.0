<!-- Beginning of pages/pallet/form.blade.php -->

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

@include('fields.textEntry', ['fieldName' => 'Pallet_ID' ])
@if(Entrust::hasRole(['support']))
    @include('fields.textEntry', ['fieldName' => 'x'         ])
    @include('fields.textEntry', ['fieldName' => 'y'         ])
    @include('fields.textEntry', ['fieldName' => 'z'         ])
@endif
@include('fields.ddList'   , ['fieldName' => 'Status', 'lists' => (['0' => Lang::get('labels.enter.Status')]+Lang::get('lists.pallet.status'))])

@include('fields.button')

<!-- End of pages/pallet/form.blade.php -->
