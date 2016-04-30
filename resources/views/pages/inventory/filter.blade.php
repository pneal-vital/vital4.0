<!-- Beginning of pages/inventory/filter.blade.php -->

{{--
    * Table Structure
    * desc Inventory;
    +------------+-------------+------+-----+---------+-------+
    | Field      | Type        | Null | Key | Default | Extra |
    +------------+-------------+------+-----+---------+-------+
    | objectID   | bigint(20)  | NO   | PRI | NULL    |       |
    | Item       | varchar(85) | YES  | MUL | NULL    |       | => Item.objectID should accept Item.objectID or Item.Client_SKU, and diplay Item.Client_SKU
    | Quantity   | varchar(85) | YES  |     | NULL    |       |
    | Created    | varchar(85) | YES  |     | NULL    |       |
    | Status     | varchar(85) | YES  | MUL | NULL    |       |
    | Order_Line | varchar(85) | YES  | MUL | NULL    |       | => Inbound_Order | Outbound_Order.objectID
    | UOM        | varchar(85) | YES  |     |         |       | => UOM.objectID
    +------------+-------------+------+-----+---------+-------+
    7 rows in set (0.01 sec)
--}}

<!-- TODO this next line should lead to a filtered list of UPCs -->
@include('fields.textEntry', ['fieldName' => 'Item'       ])
@include('fields.dateEntry', ['fieldName' => 'Created', 'fieldFormat' => 'Y-m-d H:i', 'validateOnBlur' => 'false', 'onChangeSubmit' => 'true' ])
@include('fields.ddList'   , ['fieldName' => 'Status', 'lists' => (['0' => Lang::get('labels.enter.Status')]+Lang::get('lists.inventory.status')), 'onChangeSubmit' => 'true' ])
<!-- TODO this next line should lead to a filtered list of UPCs -->
@include('fields.textEntry', ['fieldName' => 'Order_Line' ])
@include('fields.ddList'   , ['fieldName' => 'UOM', 'lists' => $uoms, 'onChangeSubmit' => 'true'])

@include('fields.button')

<!-- End of pages/inventory/filter.blade.php -->
