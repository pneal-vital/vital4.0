<!-- Beginning of pages/receiveArticle/list.blade.php  -->

{{--
    * from PurchaseOrderDetail
    +--------------+-------------+------+-----+---------+-------+
    | Field        | Type        | Null | Key | Default | Extra |
    +--------------+-------------+------+-----+---------+-------+
	| objectID     | bigint(20)  | NO   | PRI | NULL    |       | as purchaseOrderDetailID
    | Expected_Qty | varchar(85) | YES  |     | NULL    |       |
    +--------------+-------------+------+-----+---------+-------+
+
    * from Article
    +--------------------+--------------+------+-----+---------+-------+
    | Field              | Type         | Null | Key | Default | Extra |
    +--------------------+--------------+------+-----+---------+-------+
    | objectID           | bigint(20)   | NO   | PRI | NULL    |       | as articleID
    | Client_SKU         | varchar(85)  | YES  | MUL | NULL    |       |
    | Description        | varchar(255) | YES  |     | NULL    |       |
    | UOM                | varchar(85)  | YES  |     | NULL    |       |
    | Case_Pack          | varchar(85)  | YES  |     | NULL    |       |
    | Colour             | varchar(85)  | YES  |     | NULL    |       |
    | Zone               | varchar(85)  | YES  |     | NULL    |       |
    | rework             | varchar(85)  | YES  |     | NULL    |       |
    +--------------------+--------------+------+-----+---------+-------+
as $receiveArticle
--}}

<table class="table table-bordered">
    <tr>
        @if(Entrust::hasRole(['support']))
            <th>{!! Lang::get('labels.Article')             !!}</th>
            <th>{!! Lang::get('labels.Expected_Qty')        !!}</th>
        @endif
        <th>{!! Lang::get('labels.Client_SKU')          !!}</th>
        <th>{!! Lang::get('labels.Description')         !!}</th>
        @if(Entrust::hasRole(['support']))
            <th>{!! Lang::get('labels.UOM')                 !!}</th>
            <th>{!! Lang::get('labels.Case_Pack')           !!}</th>
            <th>{!! Lang::get('labels.Colour')              !!}</th>
            <th>{!! Lang::get('labels.Zone')                !!}</th>
        @endif
        <th>{!! Lang::get('labels.rework')              !!}</th>
    </tr>


    @foreach($receiveArticles as $ra)

        <tr>
            @if(Entrust::hasRole(['support']))
                <td>{!! link_to_route((isset($route) ? $route : 'receiveArticle.show'), $ra['purchaseOrderDetailID'], ['id' => $ra['purchaseOrderDetailID']]) !!}</td>
                <td>{{ $ra['Expected_Qty'] }}</td>
            @endif
            <td>{!! link_to_route((isset($route) ? $route : 'receiveArticle.show'), $ra['Client_SKU'], ['id' => $ra['purchaseOrderDetailID']]) !!}</td>
            <td>{{ $ra['Description']  }}</td>
            @if(Entrust::hasRole(['support']))
                <td>{{ $uoms[$ra['UOM']]   }}</td>
                <td>{{ $ra['Case_Pack']    }}</td>
                <td>{{ $ra['Colour']       }}</td>
                <td>{{ $ra['Zone']         }}</td>
            @endif
            <td>{{ $ra['rework']       }}</td>
        </tr>

    @endforeach

</table>

{!! isset($receiveArticle) ? $receiveArticles->appends($receiveArticle)->render() : $receiveArticles->render() !!}

<!-- End of pages/receiveArticle/list.blade.php -->
