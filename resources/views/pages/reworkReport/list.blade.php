<!-- Beginning of pages/reworkReport/list.blade.php  -->

{{--
    * Rework Report;
    +----------------+---------------------+
    | Field          | Type                |
    +----------------+---------------------+
    | Purchase_Order | varchar(85)         |
    | PO_Class       | varchar(45)         |
    | Client_SKU     | varchar(85)         |
    | Expected_Qty   | int(11)             |
    | Actual_Qty     | int(11)             |
    | Variance       | int(11)             |
    | fromDate       | timestamp           |
    | toDate         | timestamp           |
    | User_Name      | varchar(85)         |
    | Status         | varchar(85)         |
    | rework         | varchar(85)         |
    +----------------+---------------------+
--}}

<table class="table table-bordered">
    <tr>
        <th>{!! Lang::get('labels.Purchase_Order') !!}</th>
        <th>{!! Lang::get('labels.PO_Class')       !!}</th>
        <th>{!! Lang::get('labels.Client_SKU')     !!}</th>
        <th>{!! Lang::get('labels.Expected_Qty')   !!}</th>
        <th>{!! Lang::get('labels.Actual_Qty')     !!}</th>
        <th>{!! Lang::get('labels.Variance')       !!}</th>
        <th>{!! Lang::get('labels.fromDate')       !!}</th>
        <th>{!! Lang::get('labels.toDate')         !!}</th>
        <th>{!! Lang::get('labels.User_Name')      !!}</th>
        <th>{!! Lang::get('labels.Status')         !!}</th>
        <th>{!! Lang::get('labels.rework')         !!}</th>
    </tr>

    @foreach($reworkReports as $rr)
        <tr>
            <td>{!! link_to_route((isset($route) ? $route : 'po.show'), $rr->Purchase_Order, ['id' => $rr->Purchase_Order]) !!}</td>
            <td>{{ $rr->PO_Class       }}</td>
            <td>{!! link_to_route('upc.show', $rr->Client_SKU, ['id' => $rr->upcID]) !!}</td>
            <td>{{ $rr->Expected_Qty   }}</td>
            <td>{{ $rr->Actual_Qty     }}</td>
            <td>{{ $rr->Variance       }}</td>
            <td>{{ $rr->fromDate       }}</td>
            <td>{{ $rr->toDate         }}</td>
            <td>{{ $rr->User_Name      }}</td>
            <td>{{ $rr->Status         }}</td>
            <td>{{ $rr->rework         }}</td>
        </tr>
    @endforeach
</table>

<!-- End of pages/reworkReport/list.blade.php -->

