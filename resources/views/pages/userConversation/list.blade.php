<!-- Beginning of pages/userConversation/list.blade.php  -->

{{--
    * Table Structure
    * desc User_Conversation;
    +-------------+---------------------+------+-----+---------------------+----------------+
    | Field       | Type                | Null | Key | Default             | Extra          |
    +-------------+---------------------+------+-----+---------------------+----------------+
    | activityID  | bigint(20) unsigned | NO   | PRI | NULL                | auto_increment |
    | POD         | bigint(20)          | NO   |     | NULL                |                |
    | Article     | bigint(20)          | NO   |     | NULL                |                |
    | User_Name   | varchar(85)         | NO   |     | NULL                |                |
    | Sender_Name | varchar(85)         | NO   |     | NULL                |                |
    | created_at  | timestamp           | NO   |     | 0000-00-00 00:00:00 |                |
    | updated_at  | timestamp           | NO   |     | 0000-00-00 00:00:00 |                |
    | Text        | text                | NO   |     | NULL                |                |
    +-------------+---------------------+------+-----+---------------------+----------------+
    8 rows in set (0.01 sec)
--}}

<table class="table">
    <tr>
        @if(Entrust::hasRole(['support']))
            @unless(isset($hideActivityID))
                <th>{!! Lang::get('labels.activityID') !!}</th>
            @endunless
        @endif
        <th>{!! Lang::get('labels.POD')         !!}</th>
        <th>{!! Lang::get('labels.Article')     !!}</th>
        <th>{!! Lang::get('labels.User_Name')   !!}</th>
        <th>{!! Lang::get('labels.Sender_Name') !!}</th>
        <th>{!! Lang::get('labels.created_at')  !!}</th>
        <th>{!! Lang::get('labels.updated_at')  !!}</th>
        <th>{!! Lang::get('labels.Text')        !!}</th>
    </tr>

    @foreach($userConversations as $uc)
        <tr>
            @if(Entrust::hasRole(['support']))
                @unless(isset($hideActivityID))
                    <td>{!! link_to_route('userConversation.show', $uc->activityID, ['id' => $uc->activityID]) !!}</td>
                @endunless
            @endif
            @if(isset($uc->POD) and $uc->POD > 0)
                <td>{!! link_to_route('pod.show', $uc->POD, ['id' => $uc->POD]) !!}</td>
            @else
                <td></td>
            @endif
            @if(isset($uc->Article) and $uc->Article > 0)
                <td>{!! link_to_route('article.show', $uc->Article, ['id' => $uc->Article]) !!}</td>
            @else
                <td></td>
            @endif
            <td>{{ $uc->User_Name   }}</td>
            <td>{{ $uc->Sender_Name }}</td>
            <td>{{ $uc->created_at  }}</td>
            <td>{{ $uc->updated_at  }}</td>
            <td>
                @for($i = 0; $i < count(($lines = explode(' - ', $uc->Text))); $i++)
                    @if($i == 0)
                        {{ $lines[$i] }}
                    @else
                        <br>{{ $lines[$i] }}
                    @endif
                @endfor
            </td>
        </tr>
    @endforeach
</table>

{!! isset($userConversation) ? $userConversations->appends($userConversation)->render() : $userConversations->render() !!}

<!-- End of pages/userConversation/list.blade.php -->

