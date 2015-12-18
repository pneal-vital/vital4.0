<!-- Beginning of pages/upc/form.blade.php -->

{{--
    * UPC
	+--------------------+--------------+------+-----+---------+-------+
	| Field              | Type         | Null | Key | Default | Extra |
	+--------------------+--------------+------+-----+---------+-------+
	| parentID           | bigint(20)   | NO   | PRI | NULL    |       |
	| Quantity           | bigint(20)   | NO   |     | NULL    |       |
	| objectID           | bigint(20)   | NO   | PRI | NULL    |       |
	| Sku_Number         | varchar(85)  | YES  |     | NULL    |       |
	| Client_Code        | varchar(85)  | YES  | MUL | NULL    |       |
	| Client_SKU         | varchar(85)  | YES  | MUL | NULL    |       |
	| Description        | varchar(255) | YES  |     | NULL    |       |
	| UOM                | varchar(85)  | YES  |     | NULL    |       |
	| Retail_Price       | varchar(85)  | YES  |     | NULL    |       |
	| UPC                | varchar(85)  | YES  | MUL | NULL    |       |
	| Colour             | varchar(85)  | YES  |     | NULL    |       |
	| Zone               | varchar(85)  | YES  |     | NULL    |       |
	| Description_2      | varchar(255) | YES  |     | NULL    |       |
	+--------------------+--------------+------+-----+---------+-------+
--}}

@include('fields.textEntry', ['fieldName' => 'Sku_Number'      ])
@include('fields.ddList'   , ['fieldName' => 'Client_Code', 'lists' => $clients])
@include('fields.textEntry', ['fieldName' => 'Client_SKU'      ])
@include('fields.textEntry', ['fieldName' => 'Description'     ])
@include('fields.ddList'   , ['fieldName' => 'UOM', 'lists' => $uoms])
@include('fields.textEntry', ['fieldName' => 'Retail_Price'    ])
@include('fields.textEntry', ['fieldName' => 'UPC'             ])
@include('fields.textEntry', ['fieldName' => 'Colour'          ])
@include('fields.textEntry', ['fieldName' => 'Zone'            ])
@include('fields.textEntry', ['fieldName' => 'Description_2'   ])

@include('fields.button')

<!-- End of pages/upc/form.blade.php -->
