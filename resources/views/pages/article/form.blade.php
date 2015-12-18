<!-- Beginning of pages/article/form.blade.php -->

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
    | split              | varchar(85)  | YES  |     | NULL    |       |
    +--------------------+--------------+------+-----+---------+-------+
--}}

@include('fields.textEntry', ['fieldName' => 'Sku_Number'         ])
@include('fields.ddList'   , ['fieldName' => 'Client_Code', 'lists' => $clients])
@include('fields.textEntry', ['fieldName' => 'Client_SKU'         ])
@include('fields.textEntry', ['fieldName' => 'Description'        ])
@include('fields.ddList'   , ['fieldName' => 'UOM', 'lists' => $uoms])
@include('fields.textEntry', ['fieldName' => 'Per_Unit_Weight'    ])
@include('fields.textEntry', ['fieldName' => 'Retail_Price'       ])
@include('fields.textEntry', ['fieldName' => 'Case_Pack'          ])
@include('fields.textEntry', ['fieldName' => 'UPC'                ])
@include('fields.textEntry', ['fieldName' => 'Colour'             ])
@include('fields.textEntry', ['fieldName' => 'Zone'               ])
@include('fields.textEntry', ['fieldName' => 'Description_2'      ])
@include('fields.textEntry', ['fieldName' => 'Master_Pack_Weight' ])
@include('fields.textEntry', ['fieldName' => 'opening'            ])
@include('fields.textEntry', ['fieldName' => 'replen'             ])
@include('fields.ddList'   , ['fieldName' => 'rework', 'lists' => array_merge(Lang::get('lists.article.rework'),['' => Lang::get('labels.filter.rework')]) ])
@include('fields.ddList'   , ['fieldName' => 'split' , 'lists' => array_merge(Lang::get('lists.article.split') ,['' => Lang::get('labels.filter.split' )]) ])

@include('fields.button')

<!-- End of pages/article/form.blade.php -->
