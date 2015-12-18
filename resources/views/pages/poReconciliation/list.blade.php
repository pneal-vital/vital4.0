<!-- Beginning of pages/poReconciliation/list.blade.php  -->

{{--
    * from PurchaseOrderDetail
    +--------------+-------------+------+-----+---------+-------+
    | Field        | Type        | Null | Key | Default | Extra |
    +--------------+-------------+------+-----+---------+-------+
	| objectID     | bigint(20)  | NO   | PRI | NULL    |       | as purchaseOrderDetailID
    | Expected_Qty | varchar(85) | YES  |     | NULL    |       |
    +--------------+-------------+------+-----+---------+-------+
+
    | Received_Qty | varchar(85) | NO   |     | '0/0'   |       | <= calculated value
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
        <th>{!! Lang::get('labels.Article')             !!}</th>
        <th>{!! Lang::get('labels.Expected_Qty')        !!}</th>
        <th>{!! Lang::get('labels.Received_Qty')        !!}</th>
        <th>{!! Lang::get('labels.Client_SKU')          !!}</th>
        <th>{!! Lang::get('labels.Description')         !!}</th>
        <th>{!! Lang::get('labels.UOM')                 !!}</th>
        <th>{!! Lang::get('labels.Case_Pack')           !!}</th>
        <th>{!! Lang::get('labels.Zone')                !!}</th>
        <th>{!! Lang::get('labels.rework')              !!}</th>
    </tr>


    @foreach($receiveArticles as $receiveArticle)

        <tr class="{{ $receiveArticle->status }}">
            <td>{!! link_to_route((isset($route) ? $route : 'receiveArticle.show'), $receiveArticle->purchaseOrderDetailID, ['id' => $receiveArticle->purchaseOrderDetailID]) !!}</td>
            <td>{{ $receiveArticle->Expected_Qty }}</td>
            <td>{{ $receiveArticle->Received_Qty }}</td>
            <td>{{ $receiveArticle->Client_SKU   }}</td>
            <td>{{ $receiveArticle->Description  }}</td>
            <td>{{ $uoms[$receiveArticle->UOM]   }}</td>
            <td>{{ $receiveArticle->Case_Pack    }}</td>
            <td>{{ $receiveArticle->Zone         }}</td>
            <td>{{ $receiveArticle->rework       }}</td>
        </tr>

    @endforeach

</table>

{{-- isset($receiveArticle) ? $receiveArticles->appends($receiveArticle)->render() : $receiveArticles->render() --}}
{!! $receiveArticles->render() !!}

<!-- End of pages/poReconciliation/list.blade.php -->
