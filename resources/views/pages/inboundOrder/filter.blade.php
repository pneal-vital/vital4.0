<!-- Beginning of pages/inboundOrder/filter.blade.php -->

{{--
    * Table Structure
    * desc Inbound_Order;
    +----------------------+-------------+------+-----+---------+-------+
    | Field                | Type        | Null | Key | Default | Extra |
    +----------------------+-------------+------+-----+---------+-------+
    | objectID             | bigint(20)  | NO   | PRI | NULL    |       |
    | Order_Number         | varchar(85) | YES  |     | NULL    |       |
    | Client               | varchar(85) | YES  |     | NULL    |       |
    | Purchase_Order       | varchar(85) | YES  | MUL | NULL    |       |
    | Invoice_Number       | varchar(85) | YES  |     | NULL    |       |
    | Status               | varchar(85) | YES  | MUL | NULL    |       |
    | Created              | varchar(85) | YES  |     | NULL    |       |
    | Expected             | varchar(85) | YES  |     | NULL    |       |
    +----------------------+-------------+------+-----+---------+-------+
--}}

@include('fields.textEntry', ['fieldName' => 'Order_Number'   ])
@include('fields.ddList'   , ['fieldName' => 'Client', 'lists' => $clients, 'onChangeSubmit' => 'true' ])
@include('fields.textEntry', ['fieldName' => 'Purchase_Order' ])
@include('fields.textEntry', ['fieldName' => 'Invoice_Number' ])
@include('fields.ddList'   , ['fieldName' => 'Status', 'lists' => (['0' => Lang::get('labels.enter.Status')]+Lang::get('lists.inboundOrder.status')), 'onChangeSubmit' => 'true' ])
@include('fields.dateEntry', ['fieldName' => 'Created' , 'fieldFormat' => 'Y-m-d H:i', 'validateOnBlur' => 'false', 'onChangeSubmit' => 'true' ])
@include('fields.dateEntry', ['fieldName' => 'Expected', 'fieldFormat' => 'Y-m-d H:i', 'validateOnBlur' => 'false', 'onChangeSubmit' => 'true' ])

@include('fields.button')

<!-- End of pages/inboundOrder/filter.blade.php -->
