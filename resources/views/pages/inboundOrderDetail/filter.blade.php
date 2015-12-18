<!-- Beginning of pages/inboundOrderDetail/filter.blade.php -->

{{--
    * Table Structure
    * desc Inbound_Order_Detail;
	+--------------+-------------+------+-----+---------+-------+
	| Field        | Type        | Null | Key | Default | Extra |
	+--------------+-------------+------+-----+---------+-------+
	| objectID     | bigint(20)  | NO   | PRI | NULL    |       |
	| Order_Number | varchar(85) | YES  | MUL | NULL    |       |
	| SKU          | varchar(85) | YES  | MUL | NULL    |       | contains Article.objectID
	| Expected_Qty | varchar(85) | YES  |     | NULL    |       |
	| Actual_Qty   | varchar(85) | YES  |     | NULL    |       |
	| Status       | varchar(85) | YES  | MUL | NULL    |       |
	| UPC          | varchar(35) | YES  | MUL | NULL    |       |
	| UOM          | varchar(85) | YES  |     |         |       |
	+--------------+-------------+------+-----+---------+-------+
--}}

@include('fields.textEntry', ['fieldName' => 'Order_Number' ])
@include('fields.textEntry', ['fieldName' => 'SKU'          ])
@include('fields.textEntry', ['fieldName' => 'Expected_Qty' ])
@include('fields.textEntry', ['fieldName' => 'Actual_Qty'   ])
@include('fields.ddList'   , ['fieldName' => 'Status', 'lists' => (['0' => Lang::get('labels.enter.Status')]+Lang::get('lists.inboundOrderDetail.status')), 'onChangeSubmit' => 'true' ])
@include('fields.textEntry', ['fieldName' => 'UPC'          ])
@include('fields.ddList'   , ['fieldName' => 'UOM', 'lists' => $uoms, 'onChangeSubmit' => 'true' ])

@include('fields.button')

<!-- End of pages/inboundOrderDetail/filter.blade.php -->
