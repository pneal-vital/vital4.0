<!-- Beginning of pages/upc/list.blade.php  -->

{{--
    * UPC
	+--------------------+--------------+------+-----+---------+-------+
	| Field              | Type         | Null | Key | Default | Extra |
	+--------------------+--------------+------+-----+---------+-------+
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

<table class="table">
    <tr>
        @if(Entrust::hasRole(['support']))
            <th>{!! Lang::get('labels.UPC_Number')     !!}</th>
            <th>{!! Lang::get('labels.Sku_Number')     !!}</th>
            <th>{!! Lang::get('labels.Client_Code')    !!}</th>
        @endif
        @if(isset($quantityFor))
            <th>{!! Lang::get('labels.Quantity')       !!}</th>
        @endif
        <th>{!! Lang::get('labels.Client_SKU')     !!}</th>
        <th>{!! Lang::get('labels.Description')    !!}</th>
        <th>{!! Lang::get('labels.UOM')            !!}</th>
        @if(Entrust::hasRole(['support']))
            <th>{!! Lang::get('labels.UPC')            !!}</th>
            <th>{!! Lang::get('labels.Colour')         !!}</th>
        @endif
        @include('fields.cedIcons', ['model' => 'upc', 'elemType' => 'th'])
    </tr>

    @foreach($upcs as $u)
        <tr>
            @if(Entrust::hasRole(['support']))
                <td>{!! link_to_route('upc.show', $u->objectID, ['id' => $u->objectID]) !!}</td>
                <td>{{ $u->Sku_Number            }}</td>
                <td>{{ $clients[$u->Client_Code] }}</td>
            @endif
            @if(isset($quantityFor))
                <td>{{ $u->parents[$quantityFor]->Quantity }}</td>
            @endif
            <td>{!! link_to_route('upc.show', $u->Client_SKU, ['id' => $u->objectID]) !!}</td>
            <td>{{ $u->Description           }}</td>
            <td>{{ $uoms[$u->UOM]            }}</td>
            @if(Entrust::hasRole(['support']))
                <td>{{ $u->UPC                   }}</td>
                <td>{{ $u->Colour                }}</td>
            @endif
            @include('fields.cedIcons', ['model' => 'upc', 'elemType' => 'td', 'id' => $u->objectID])
        </tr>
    @endforeach
</table>

{!! isset($upc) ? $upcs->appends($upc)->render() : $upcs->render() !!}

@include('fields.cedIcons', ['model' => 'article', 'elemType' => 'script'])

<!-- End of pages/upc/list.blade.php -->

