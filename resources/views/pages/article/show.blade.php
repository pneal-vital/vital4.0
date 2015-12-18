@extends('pages.panelList')

@section('head')
    <!-- section('head') of pages/article/show.blade.php  -->

    @include('fields.cedIcons', ['model' => 'article', 'elemType' => 'script'])

    <!-- stop of pages/article/show.blade.php, section('head') -->
@stop

@section('title')
    <!-- section('title') of pages/article/show.blade.php  -->

    @lang('labels.titles.Article')

    <!-- stop of pages/article/show.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/article/show.blade.php  -->

    <h4 class="panel-title pull-left">
        @lang('labels.titles.Article_for') {{ $article->Client_SKU }}
    </h4>

    @include('fields.cedIcons', ['model' => 'article', 'elemType' => 'div', 'id' => $article->objectID])

    <!-- stop of pages/article/show.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/article/show.blade.php  -->

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

    @if(Entrust::hasRole(['support']))
        @include('fields.textList', ['fieldName' => 'objectID'           , 'fieldValue' => $article->objectID              ])
        @include('fields.textList', ['fieldName' => 'Sku_Number'         , 'fieldValue' => $article->Sku_Number            ])
        @include('fields.textList', ['fieldName' => 'Client_Code'        , 'fieldValue' => $clients[$article->Client_Code] ])
    @endif
    @include('fields.textList', ['fieldName' => 'Client_SKU'         , 'fieldValue' => $article->Client_SKU            ])
    @include('fields.textList', ['fieldName' => 'Description'        , 'fieldValue' => $article->Description           ])
    @include('fields.textList', ['fieldName' => 'UOM'                , 'fieldValue' => $uoms[$article->UOM]            ])
    @include('fields.textList', ['fieldName' => 'Per_Unit_Weight'    , 'fieldValue' => $article->Per_Unit_Weight       ])
    @include('fields.textList', ['fieldName' => 'Retail_Price'       , 'fieldValue' => $article->Retail_Price          ])
    @include('fields.textList', ['fieldName' => 'Case_Pack'          , 'fieldValue' => $article->Case_Pack             ])
    @if(Entrust::hasRole(['support']))
        @include('fields.textList', ['fieldName' => 'UPC'                , 'fieldValue' => $article->UPC                   ])
        @include('fields.textList', ['fieldName' => 'Colour'             , 'fieldValue' => $article->Colour                ])
        @include('fields.textList', ['fieldName' => 'Zone'               , 'fieldValue' => $article->Zone                  ])
        @include('fields.textList', ['fieldName' => 'Description_2'      , 'fieldValue' => $article->Description_2         ])
        @include('fields.textList', ['fieldName' => 'Master_Pack_Weight' , 'fieldValue' => $article->Master_Pack_Weight    ])
        @include('fields.textList', ['fieldName' => 'opening'            , 'fieldValue' => (isset($article->opening) ? $article->opening : '&nbsp;') ])
        @include('fields.textList', ['fieldName' => 'replen'             , 'fieldValue' => (isset($article->replen)  ? $article->replen  : '&nbsp;') ])
    @endif
    @include('fields.textList', ['fieldName' => 'rework'             , 'fieldValue' => (substr(Lang::get('lists.article.rework.'.$article->rework),0,6) == 'lists.' ? $article->rework : Lang::get('lists.article.rework.'.$article->rework)) ])
    @include('fields.textList', ['fieldName' => 'split'              , 'fieldValue' => Lang::get('lists.article.split.'.$article->split) ])

    <!-- stop of pages/article/show.blade.php, section('form') -->
@stop

@section('list')
    <!-- section('list') of pages/article/show.blade.php  -->

    {{-- var_dump($upcs) --}}
    @if(isset($upcs) && count($upcs))
        <h3>{!! Lang::get('labels.titles.UPCs_of') !!} {{ $article->Client_SKU }}</h3>

        <!-- reuse pages.upc.list -->
        @include('pages.upc.list', ['hideArticleID' => 'true', 'quantityFor' => $article->objectID])
    @endif

    <!-- stop of pages/article/show.blade.php, section('list') -->
@stop

