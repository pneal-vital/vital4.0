<!-- Beginning of pages/inboundOrder/form.blade.php -->

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

{{-- type textfield and press tab
     see: File -> Settings (Ctrl+Alt+S) -> Editor -> Live Templates -> Laravel-Jeff-Form -> textField
     also: ~/.WebIde80/config/templates/
--}}

@include('fields.textEntry', ['fieldName' => 'Order_Number'   ])
@include('fields.ddList'   , ['fieldName' => 'Client', 'lists' => $clients])
@include('fields.textEntry', ['fieldName' => 'Purchase_Order' ])
@include('fields.textEntry', ['fieldName' => 'Invoice_Number' ])
@include('fields.ddList'   , ['fieldName' => 'Status', 'lists' => (['0' => Lang::get('labels.enter.Status')]+Lang::get('lists.inboundOrder.status'))])
@include('fields.dateEntry', ['fieldName' => 'Created'        ])
@include('fields.dateEntry', ['fieldName' => 'Expected'       ])

{{-- type submitbuton and press tab
     see: Live Templates
 --}}

@include('fields.button')

<!-- End of pages/inboundOrder/form.blade.php -->
