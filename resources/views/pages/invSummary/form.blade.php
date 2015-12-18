<!-- Beginning of pages/invSummary/form.blade.php -->

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
@include('fields.textEntry', ['fieldName' => 'pickQty'     ])
@include('fields.textEntry', ['fieldName' => 'actQty'      ])
@include('fields.textEntry', ['fieldName' => 'resQty'      ])
@include('fields.textEntry', ['fieldName' => 'replenPrty'  ])
@include('fields.dateEntry', ['fieldName' => 'created_at'  ])
@include('fields.dateEntry', ['fieldName' => 'updated_at'  ])

@include('fields.button')

<!-- End of pages/invSummary/form.blade.php -->
