<!-- Beginning of pages/article/list.blade.php  -->

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

<table class="table">
    <tr>
        @if(Entrust::hasRole(['support']))
            <th>{!! Lang::get('labels.Article_Number') !!}</th>
            <th>{!! Lang::get('labels.Sku_Number')     !!}</th>
            <th>{!! Lang::get('labels.Client_Code')    !!}</th>
        @endif
        <th>{!! Lang::get('labels.Client_SKU')     !!}</th>
        <th>{!! Lang::get('labels.Description')    !!}</th>
        <th>{!! Lang::get('labels.UPC')            !!}</th>
        <th>{!! Lang::get('labels.UOM')            !!}</th>
        @if(Entrust::hasRole(['support']))
            <th>{!! Lang::get('labels.rework')         !!}</th>
            <th>{!! Lang::get('labels.split')          !!}</th>
        @endif
        @include('fields.cedIcons', ['model' => 'article', 'elemType' => 'th'])
    </tr>

    @foreach($articles as $a)
        <tr>
            @if(Entrust::hasRole(['support']))
                <td>{!! link_to_route('article.show', $a->objectID, ['id' => $a->objectID]) !!}</td>
                <td>{{ $a->Sku_Number            }}</td>
                <td>{{ $clients[$a->Client_Code] }}</td>
            @endif
            <td>{!! link_to_route('article.show', $a->Client_SKU, ['id' => $a->objectID]) !!}</td>
            <td>{{ $a->Description           }}</td>
            <td>{{ $a->UPC                   }}</td>
            <td>{{ $uoms[$a->UOM]            }}</td>
            @if(Entrust::hasRole(['support']))
                <td>{{ (substr(Lang::get('lists.article.rework.'.$a->rework),0,6) == 'lists.' ? $a->rework : Lang::get('lists.article.rework.'.$a->rework)) }}</td>
                <td>{{ (substr(Lang::get('lists.article.split.'. $a->split), 0,6) == 'lists.' ? $a->split  : Lang::get('lists.article.split.'. $a->split) ) }}</td>
            @endif
            @include('fields.cedIcons', ['model' => 'article', 'elemType' => 'td', 'id' => $a->objectID])
        </tr>
    @endforeach
</table>

{!! isset($article) ? $articles->appends($article)->render() : $articles->render() !!}

@include('fields.cedIcons', ['model' => 'article', 'elemType' => 'script'])

<!-- End of pages/article/list.blade.php -->
