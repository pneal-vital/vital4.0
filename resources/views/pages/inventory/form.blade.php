<!-- Beginning of pages/inventory/form.blade.php -->

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

<!-- TODO this next line should lead to a filtered selection list of UPCs -->
@include('fields.textEntry', ['fieldName' => 'Item'       ])
@include('fields.textEntry', ['fieldName' => 'Quantity'   ])
@include('fields.ddList'   , ['fieldName' => 'Status', 'lists' => (['0' => Lang::get('labels.enter.Status')]+Lang::get('lists.inventory.status')) ])
<!-- TODO this next line should lead to a filtered selection list of inbound/outbound OrderDetail lines -->
@include('fields.textEntry', ['fieldName' => 'Order_Line' ])
@include('fields.ddList'   , ['fieldName' => 'UOM', 'lists' => $uoms])

@include('fields.button', ['CancelButton' => 'Cancel'])

<!-- End of pages/inventory/form.blade.php -->
