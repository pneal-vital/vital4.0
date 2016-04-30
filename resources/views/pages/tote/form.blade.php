<!-- Beginning of pages/tote/form.blade.php -->

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

@include('fields.textEntry', ['fieldName' => 'Carton_ID' ])
@include('fields.ddList'   , ['fieldName' => 'Status', 'lists' => (['0' => Lang::get('labels.enter.Status')]+Lang::get('lists.tote.status')) ])

@include('fields.button', ['CancelButton' => 'Cancel'])

<!-- End of pages/tote/form.blade.php -->
