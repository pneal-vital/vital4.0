<!-- Beginning of pages/article/filter.blade.php -->

{{--
    * Article
    +--------------------+--------------+------+-----+---------+-------+
    | Field              | Type         | Null | Key | Default | Extra |
    +--------------------+--------------+------+-----+---------+-------+
    | objectID           | bigint(20)   | NO   | PRI | NULL    |       |
    | Sku_Number         | varchar(85)  | YES  |     | NULL    |       |
    | Client_Code        | varchar(85)  | YES  | MUL | NULL    |       |
    | Client_SKU         | varchar(85)  | YES  | MUL | NULL    |       |
    | Description        | varchar(255) | YES  |     | NULL    |       |
    | UOM                | varchar(85)  | YES  |     | NULL    |       |
    | Per_Unit_Weight    | varchar(85)  | YES  |     | NULL    |       |
    | Retail_Price       | varchar(85)  | YES  |     | NULL    |       |
    | Case_Pack          | varchar(85)  | YES  |     | NULL    |       |
    | UPC                | varchar(85)  | YES  | MUL | NULL    |       |
    | Colour             | varchar(85)  | YES  |     | NULL    |       |
    | Zone               | varchar(85)  | YES  |     | NULL    |       |
    | Description_2      | varchar(255) | YES  |     | NULL    |       |
    | Master_Pack_Weight | varchar(85)  | YES  |     | NULL    |       |
    | opening            | varchar(85)  | YES  |     | NULL    |       |
    | replen             | varchar(85)  | YES  |     | NULL    |       |
    | rework             | varchar(85)  | YES  |     | NULL    |       |
    +--------------------+--------------+------+-----+---------+-------+
--}}

@if(Entrust::hasRole(['support']))
    @include('fields.textEntry', ['fieldName' => 'Sku_Number'  ])
    @include('fields.ddList'   , ['fieldName' => 'Client_Code', 'lists' => $clients, 'onChangeSubmit' => 'true' ])
@endif
@include('fields.textEntry', ['fieldName' => 'Client_SKU'  ])
@include('fields.textEntry', ['fieldName' => 'Description' ])
@include('fields.textEntry', ['fieldName' => 'UPC'         ])
@include('fields.ddList'   , ['fieldName' => 'UOM', 'lists' => $uoms, 'onChangeSubmit' => 'true' ])

@include('fields.button')

<!-- End of pages/article/filter.blade.php -->
