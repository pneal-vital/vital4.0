@extends('pages.panelListExcel')

@section('title')
    <!-- section('title') of pages/reworkReport/excel.blade.php  -->
    <!-- hack {{ $columnCount = count($reworkReports[0]) }} -->

    <td class="page-title" colspan="{{ $columnCount }}">
        <h1>
            @lang('labels.titles.Rework_Report')
        </h1>
    </td>

    <!-- stop of pages/reworkReport/excel.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/reworkReport/excel.blade.php  -->
    <!-- hack {{ $usedCount = ((count($reworkReport) - 2) * 2 > $columnCount ? $columnCount : (count($reworkReport) - 2) * 2) }} -->

    <td class="panel-title" colspan="{{ $usedCount }}">
        <h3>
            @lang('labels.titles.Rework_Report_Filter')
        </h3>
    </td>
    @for($i = $usedCount; $i < $columnCount; $i++)
        <td></td>
    @endfor

    <!-- stop of pages/reworkReport/excel.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/reworkReport/excel.blade.php  -->

    {{-- Filter fields --}}
    <td class="fieldName">{!! Lang::get('labels.fromDate') !!}</td><td>{{ $reworkReport['fromDate'] }}</td>
    <td class="fieldName">{!! Lang::get('labels.toDate'  ) !!}</td><td>{{ $reworkReport['toDate'  ] }}</td>
    @for($i = $usedCount; $i < $columnCount; $i++)
        <td></td>
    @endfor

    <!-- stop of pages/reworkReport/excel.blade.php, section('form') -->
@stop

@section('list')
    <!-- section('list') of pages/reworkReport/excel.blade.php  -->

    <tr>
        @for($i = 0; $i < $columnCount; $i++)
            <td></td>
        @endfor
    </tr>
    {{-- Filtered list --}}
    <tr>
        <td class="th">{!! Lang::get('labels.Purchase_Order') !!}</td>
        <td class="th">{!! Lang::get('labels.PO_Class')       !!}</td>
        <td class="th">{!! Lang::get('labels.Client_SKU')     !!}</td>
        <td class="th">{!! Lang::get('labels.Expected_Qty')   !!}</td>
        <td class="th">{!! Lang::get('labels.Actual_Qty')     !!}</td>
        <td class="th">{!! Lang::get('labels.Variance')       !!}</td>
        <td class="th">{!! Lang::get('labels.fromDate')       !!}</td>
        <td class="th">{!! Lang::get('labels.toDate')         !!}</td>
        <td class="th">{!! Lang::get('labels.User_Name')      !!}</td>
        <td class="th">{!! Lang::get('labels.Status')         !!}</td>
        <td class="th">{!! Lang::get('labels.rework')         !!}</td>
    </tr>

    @foreach($reworkReports as $rr)
        <tr>
            <td>{{ $rr->Purchase_Order }}</td>
            <td>{{ $rr->PO_Class       }}</td>
            <td>{{ $rr->Client_SKU     }}</td>
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

    <!-- stop of pages/reworkReport/excel.blade.php, section('list') -->
@stop
