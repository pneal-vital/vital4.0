<!-- Beginning of pages/userActivity/list.blade.php  -->

{{--
    * Table Structure
    * desc User_Activity;
    +------------+---------------------+------+-----+---------------------+----------------+
    | Field      | Type                | Null | Key | Default             | Extra          |
    +------------+---------------------+------+-----+---------------------+----------------+
    | activityID | bigint(20) unsigned | NO   | PRI | NULL                | auto_increment |
    | id         | bigint(20)          | NO   |     | NULL                |                |
    | classID    | varchar(85)         | NO   |     | NULL                |                |
    | User_Name  | varchar(85)         | NO   |     | NULL                |                |
    | created_at | timestamp           | NO   |     | 0000-00-00 00:00:00 |                |
    | updated_at | timestamp           | NO   |     | 0000-00-00 00:00:00 |                |
    | Purpose    | varchar(85)         | NO   |     | NULL                |                |
    +------------+---------------------+------+-----+---------------------+----------------+
    7 rows in set (0.00 sec)
--}}

<table class="table">
    <tr>
        @if(Entrust::hasRole(['support']))
            @unless(isset($hideActivityID))
                <th>{!! Lang::get('labels.activityID') !!}</th>
            @endunless
            <th>{!! Lang::get('labels.id')         !!}</th>
        @endif
        <th>{!! Lang::get('labels.classID')    !!}</th>
        <th>{!! Lang::get('labels.User_Name')  !!}</th>
        <th>{!! Lang::get('labels.created_at') !!}</th>
        <th>{!! Lang::get('labels.updated_at') !!}</th>
        <th>{!! Lang::get('labels.Purpose')    !!}</th>
    </tr>

    @foreach($userActivities as $ua)
        <tr>
            @if(Entrust::hasRole(['support']))
                @unless(isset($hideActivityID))
                    <td>{!! link_to_route('userActivity.show', $ua->activityID, ['id' => $ua->activityID]) !!}</td>
                @endunless
                @if(isset($ua->classID))
                    <td>{!! link_to_route(\Config::get("constants.routeName.$ua->classID.show"), $ua->id, ['id' => $ua->id]) !!}</td>
                @else
                    <td>{{ $ua->id     }}</td>
                @endif
            @endif
            @if(isset($ua->classID))
                <td>{!! link_to_route(\Config::get("constants.routeName.$ua->classID.show"), $ua->classID, ['id' => $ua->id]) !!}</td>
            @else
                <td>{{ $ua->classID }}</td>
            @endif
            <td>{{ $ua->User_Name  }}</td>
            <td>{{ $ua->created_at }}</td>
            <td>{{ $ua->updated_at }}</td>
            <td>{{ $ua->Purpose    }}</td>
        </tr>
    @endforeach
</table>

{!! isset($userActivity) ? $userActivities->appends($userActivity)->render() : $userActivities->render() !!}

<!-- End of pages/userActivity/list.blade.php -->

