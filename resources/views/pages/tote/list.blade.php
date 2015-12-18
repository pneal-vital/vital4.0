<!-- Beginning of pages/tote/list.blade.php  -->

{{--
    * desc Generic_Container;
    +-----------+-------------+------+-----+---------+-------+
    | Field     | Type        | Null | Key | Default | Extra |
    +-----------+-------------+------+-----+---------+-------+
    | objectID  | bigint(20)  | NO   | PRI | NULL    |       |
    | Carton_ID | varchar(85) | YES  | MUL | NULL    |       | contains a LPN (example '52 0015 9955'), or => Generic_Container, Pallet or Pick
    | Status    | varchar(85) | YES  |     | OPEN    |       | values in ('OPEN', 'LOADED')
    +-----------+-------------+------+-----+---------+-------+
--}}

<table class="table">
    <tr>
        @if(Entrust::hasRole(['support']))
            <th>{!! Lang::get('labels.objectID')  !!}</th>
        @endif
        <th>{!! Lang::get('labels.Carton_ID') !!}</th>
        <th>{!! Lang::get('labels.Status')    !!}</th>
    </tr>

    @foreach($totes as $t)
        <tr>
            @if(Entrust::hasRole(['support']))
                <td>{!! link_to_route('tote.show', $t->objectID, ['id' => $t->objectID]) !!}</td>
            @endif
            <td>{!! link_to_route('tote.show', $t->Carton_ID, ['id' => $t->objectID]) !!}</td>
            <td>{{ Lang::get('lists.tote.status.'.$t->Status) }}</td>
        </tr>
    @endforeach
</table>

{!! isset($tote) ? $totes->appends($tote)->render() : $totes->render() !!}

<!-- End of pages/tote/list.blade.php -->

