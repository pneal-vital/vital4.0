<!-- Beginning of pages/location/list.blade.php  -->

{{--
    * Location;
    +---------------+-------------+------+-----+---------+-------+
    | Field         | Type        | Null | Key | Default | Extra |
    +---------------+-------------+------+-----+---------+-------+
    | objectID      | bigint(20)  | NO   | PRI | NULL    |       |
    | Location_Name | varchar(85) | YES  | MUL | NULL    |       |
    | Capacity      | varchar(85) | YES  |     | NULL    |       | in ('', 1, 6, 999, 9999), set to 1
    | x             | varchar(85) | YES  |     | NULL    |       |
    | y             | varchar(85) | YES  |     | NULL    |       |
    | z             | varchar(85) | YES  |     | NULL    |       |
    | LocType       | varchar(85) | YES  | MUL | NULL    |       | may be '', 'ACTIVITY', 'RESERVE', 'WORK', or 'PICK' + pick Sequence number
    | Comingle      | varchar(85) | YES  |     | NULL    |       | in ('N', 'P')
    +---------------+-------------+------+-----+---------+-------+
--}}

<table class="table table-bordered">
    <tr>
        @if(Entrust::hasRole(['support']))
            <th>{!! Lang::get('labels.objectID')      !!}</th>
        @endif
        <th>{!! Lang::get('labels.Location_Name') !!}</th>
        @if(Entrust::hasRole(['support']))
            <th>{!! Lang::get('labels.Capacity')      !!}</th>
            <th>{!! Lang::get('labels.x')             !!}</th>
            <th>{!! Lang::get('labels.y')             !!}</th>
            <th>{!! Lang::get('labels.z')             !!}</th>
        @endif
        <th>{!! Lang::get('labels.LocType')       !!}</th>
        <th>{!! Lang::get('labels.Comingle')      !!}</th>
    </tr>

    @foreach($locations as $loc)
        <tr>
            @if(Entrust::hasRole(['support']))
                <td>{!! link_to_route((isset($route) ? $route : 'location.show'), $loc->objectID, ['id' => $loc->objectID]) !!}</td>
            @endif
            <td>{!! link_to_route((isset($route) ? $route : 'location.show'), $loc->Location_Name, ['id' => $loc->objectID]) !!}</td>
            @if(Entrust::hasRole(['support']))
                <td>{{ $loc->Capacity      }}</td>
                <td>{{ $loc->x             }}</td>
                <td>{{ $loc->y             }}</td>
                <td>{{ $loc->z             }}</td>
            @endif
            <td>{{ $loc->LocType       }}</td>
            <td>{{ $loc->Comingle      }}</td>
        </tr>
    @endforeach
</table>

{!! isset($location) ? $locations->appends($location)->render() : $locations->render() !!}

<!-- End of pages/location/list.blade.php -->

