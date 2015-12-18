@extends('pages.panel')

@section('head')
    <!-- section('head') of pages/upc/show.blade.php  -->

    @include('fields.cedIcons', ['model' => 'upc', 'elemType' => 'script'])

    <!-- stop of pages/upc/show.blade.php, section('head') -->
@stop

@section('panel')
    <!-- section('panel') of pages/upc/show.blade.php  -->

    <div class="panel-heading clearfix">
        <h4 class="panel-title pull-left">
            @lang('labels.titles.UPC_for') {{ $upc->Client_SKU }}
        </h4>

        @include('fields.cedIcons', ['model' => 'upc', 'elemType' => 'div', 'id' => $upc->objectID])
    </div>

    <div class="panel-body">

        @include('errors.list')

        {{--
        * UPC
        +--------------------+--------------+------+-----+---------+-------+
        | Field              | Type         | Null | Key | Default | Extra |
        +--------------------+--------------+------+-----+---------+-------+
        | parentID           | bigint(20)   | NO   | PRI | NULL    |       |
        | parentSKU          | bigint(20)   | NO   | PRI | NULL    |       |
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

        <!--
        @if(Entrust::hasRole(['support']))
            {{ $labelSize = 'col-md-2' }}
            {{ $fieldSize = 'col-md-2' }}
        @else
            {{ $labelSize = 'col-md-2' }}
            {{ $fieldSize = 'col-md-4' }}
        @endif
         -->
        @foreach($upc->parents as $parent)
            <div class="row">
                @if(Entrust::hasRole(['support']))
                    @include('fields.textList', ['fieldName' => 'parentID' , 'fieldValue' => $parent->parentID , 'urlName' => 'article.show'])
                @endif
                @include('fields.textList', ['fieldName' => 'parentSKU'    , 'fieldValue' => $parent->parentSKU, 'urlName' => 'article.show', 'urlID' => $parent->parentID ])
                @include('fields.textList', ['fieldName' => 'Quantity'     , 'fieldValue' => $parent->Quantity    ])
            </div>
        @endforeach
        <!--
         {{ $labelSize = 'col-md-4' }}
         {{ $fieldSize = 'col-md-8' }}
         -->

        @if(Entrust::hasRole(['support']))
            @include('fields.textList', ['fieldName' => 'objectID'     , 'fieldValue' => $upc->objectID          ])
            @include('fields.textList', ['fieldName' => 'Sku_Number'   , 'fieldValue' => $upc->Sku_Number            ])
            @include('fields.textList', ['fieldName' => 'Client_Code'  , 'fieldValue' => $clients[$upc->Client_Code] ])
        @endif
        @include('fields.textList', ['fieldName' => 'Client_SKU'   , 'fieldValue' => $upc->Client_SKU            ])
        @include('fields.textList', ['fieldName' => 'Description'  , 'fieldValue' => $upc->Description           ])
        @include('fields.textList', ['fieldName' => 'UOM'          , 'fieldValue' => $uoms[$upc->UOM]            ])
        @include('fields.textList', ['fieldName' => 'Retail_Price' , 'fieldValue' => $upc->Retail_Price          ])
        @if(Entrust::hasRole(['support']))
            @include('fields.textList', ['fieldName' => 'UPC'          , 'fieldValue' => $upc->UPC                   ])
            @include('fields.textList', ['fieldName' => 'Colour'       , 'fieldValue' => $upc->Colour                ])
            @include('fields.textList', ['fieldName' => 'Zone'         , 'fieldValue' => $upc->Zone                  ])
            @include('fields.textList', ['fieldName' => 'Description_2', 'fieldValue' => $upc->Description_2         ])
        @endif

    </div>

    <!-- stop of pages/upc/show.blade.php, section('panel') -->
@stop

