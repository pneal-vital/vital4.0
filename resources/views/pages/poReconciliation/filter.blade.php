<!-- Beginning of pages/poReconciliation/filter.blade.php  -->

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

@include('fields.textEntry', ['fieldName' => 'Purchase_Order'])
@if(Entrust::hasRole(['support']))
    @include('fields.ddList'   , ['fieldName' => 'Client', 'lists' => $clients, 'onChangeSubmit' => 'true' ])
    @include('fields.textEntry', ['fieldName' => 'Order_Number'  ])
    @include('fields.textEntry', ['fieldName' => 'Invoice_Number'])
@endif
@include('fields.ddList'   , ['fieldName' => 'Status', 'lists' => $statuses, 'onChangeSubmit' => 'true' ])
@if(Entrust::hasRole(['support']))
    @include('fields.dateEntry', ['fieldName' => 'Created', 'fieldFormat' => 'Y-m-d H:i', 'validateOnBlur' => 'false', 'onChangeSubmit' => 'true' ])
@endif
@include('fields.dateEntry', ['fieldName' => 'Expected', 'fieldFormat' => 'Y-m-d H:i', 'validateOnBlur' => 'false', 'onChangeSubmit' => 'true' ])
@include('fields.button')

<!-- End of pages/poReconciliation/filter.blade.php -->
