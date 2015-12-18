<!-- Beginning of pages/invSummary/filter.blade.php -->

{{--
    * Table Structure
    * desc Inventory_Summary;
    +-------------+--------------+------+-----+---------------------+-------+
    | Field       | Type         | Null | Key | Default             | Extra |
    +-------------+--------------+------+-----+---------------------+-------+
    | objectID    | bigint(20)   | NO   | PRI | NULL                |       |
    | Client_SKU  | varchar(85)  | YES  |     | NULL                |       |
    | Description | varchar(255) | YES  |     | NULL                |       |
    | pickQty     | int(10)      | NO   |     | NULL                |       |
    | actQty      | int(10)      | NO   |     | NULL                |       |
    | resQty      | int(10)      | NO   |     | NULL                |       |
    | replenPrty  | int(10)      | YES  |     | NULL                |       |
    | created_at  | timestamp    | NO   |     | 0000-00-00 00:00:00 |       |
    | updated_at  | timestamp    | NO   |     | 0000-00-00 00:00:00 |       |
    +-------------+--------------+------+-----+---------------------+-------+
    9 rows in set (0.03 sec)
--}}

@if(Entrust::hasRole(['support']))
    @include('fields.textEntry', ['fieldName' => 'objectID'    ])
@endif
@include('fields.textEntry', ['fieldName' => 'Client_SKU'  ])
@include('fields.textEntry', ['fieldName' => 'Description' ])
@include('fields.radio'    , ['fieldName' => 'pickQty'   , 'lists' => Lang::get('lists.invSummary.pickQty'   ), 'isChecked' => (isset($invSummary['pickQty_rb'   ]) ? $invSummary['pickQty_rb'   ] : '') ])
@include('fields.radio'    , ['fieldName' => 'actQty'    , 'lists' => Lang::get('lists.invSummary.actQty'    ), 'isChecked' => (isset($invSummary['actQty_rb'    ]) ? $invSummary['actQty_rb'    ] : '') ])
@include('fields.radio'    , ['fieldName' => 'resQty'    , 'lists' => Lang::get('lists.invSummary.resQty'    ), 'isChecked' => (isset($invSummary['resQty_rb'    ]) ? $invSummary['resQty_rb'    ] : '') ])
<!-- This line produces a list of replenPrty checkbox items that were 'on', such as ', noReplen' for $invSummary['replenPrty_cb_noReplen']
 {{ $checkedList = array_reduce(array_keys($invSummary), function($result, $item) { if(strpos(' '.$item,'replenPrty_cb_') == 1) return $result.', '.$item; else return $result; }, '' ) }} -->
@include('fields.checkBox' , ['fieldName' => 'replenPrty', 'lists' => Lang::get('lists.invSummary.replenPrty'), 'isChecked' => $checkedList ])
@include('fields.dateEntry', ['fieldName' => 'created_at', 'fieldFormat' => 'Y-m-d H:i', 'validateOnBlur' => 'false', 'onChangeSubmit' => 'true' ])
@include('fields.dateEntry', ['fieldName' => 'updated_at', 'fieldFormat' => 'Y-m-d H:i', 'validateOnBlur' => 'false', 'onChangeSubmit' => 'true' ])

@include('fields.button')

<!-- End of pages/invSummary/filter.blade.php -->
