<!-- Beginning of pages/tote/filter.blade.php -->

{{--
    * desc Generic_Container;
    +-----------+-------------+------+-----+---------+-------+
    | Field     | Type        | Null | Key | Default | Extra |
    +-----------+-------------+------+-----+---------+-------+
    | objectID  | bigint(20)  | NO   | PRI | NULL    |       |
    | Carton_ID | varchar(85) | YES  | MUL | NULL    |       | contains a LPN (example '52 0015 9955'), or => Generic_Container, Pallet or Pick
    | Status    | varchar(85) | YES  |     | OPEN    |       | values in ('OPEN', 'LOADED')
    +-----------+-------------+------+-----+---------+-------+
--}}

@if(Entrust::hasRole(['support']))
    @include('fields.textEntry', ['fieldName' => 'objectID' ])
@endif
@include('fields.textEntry', ['fieldName' => 'Carton_ID' ])
@include('fields.ddList'   , ['fieldName' => 'Status', 'lists' => $statuses, 'onChangeSubmit' => 'true' ])

@include('fields.button')

<!-- End of pages/tote/filter.blade.php -->
