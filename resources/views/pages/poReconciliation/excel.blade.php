@extends('pages.panelListExcel')

@section('title')
    <!-- section('title') of pages/poReconciliation/excel.blade.php  -->
    <!-- hack {{ $columnCount = count((array)$podArticles[0]) }} -->

    <td class="page-title" colspan="{{ $columnCount }}">
        <h1>
            @lang('labels.titles.PO_Reconciliation')
        </h1>
    </td>

    <!-- stop of pages/poReconciliation/excel.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/poReconciliation/excel.blade.php  -->
    <!-- hack {{ $usedCount = (count($filter) * 2) > $columnCount ? $columnCount : (count($filter) * 2) }} -->

    <td class="panel-title" colspan="{{ $usedCount }}">
        <h3>
            @lang('labels.titles.PO_Reconciliation_Filter')
        </h3>
    </td>
    @for($i = $usedCount; $i < $columnCount; $i++)
        <td></td>
    @endfor

    <!-- stop of pages/poReconciliation/excel.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/poReconciliation/excel.blade.php  -->

    {{-- Filter fields --}}
    <td class="fieldName">{!! Lang::get('labels.PO') !!}</td><td>{{ $filter['id'] }}</td>
    @for($i = $usedCount; $i < $columnCount; $i++)
        <td></td>
    @endfor

    <!-- stop of pages/poReconciliation/excel.blade.php, section('form') -->
@stop

@section('list')
    <!-- section('list') of pages/poReconciliation/excel.blade.php  -->

    <tr>
        @for($i = 0; $i < $columnCount; $i++)
            <td></td>
        @endfor
    </tr>
    {{-- Filtered list --}}
    <tr>
        <td class="th">{!! Lang::get('labels.Article')      !!}</td>
        <td class="th">{!! Lang::get('labels.Expected_Qty') !!}</td>
        <td class="th">{!! Lang::get('labels.Received_Qty') !!}</td>
        <td class="th">{!! Lang::get('labels.Client_SKU')   !!}</td>
        <td class="th">{!! Lang::get('labels.Description')  !!}</td>
        <td class="th">{!! Lang::get('labels.UOM')          !!}</td>
        <td class="th">{!! Lang::get('labels.Case_Pack')    !!}</td>
        <td class="th">{!! Lang::get('labels.Zone')         !!}</td>
        <td class="th">{!! Lang::get('labels.Rework')       !!}</td>
    </tr>

    @foreach($podArticles as $poda)
        <tr>
            <td>{{ $poda->articleID    }}</td>
            <td>{{ $poda->Expected_Qty }}</td>
            <td>'{{ $poda->Received_Qty }}</td>
            <td>'{{ $poda->Client_SKU   }}</td>
            <td>{{ $poda->Description  }}</td>
            <td>{{ $uoms[$poda->UOM]   }}</td>
            <td>{{ $poda->Case_Pack    }}</td>
            <td>'{{ $poda->Zone         }}</td>
            <td>{{ $poda->rework       }}</td>
        </tr>
    @endforeach

    <!-- stop of pages/poReconciliation/excel.blade.php, section('list') -->
@stop
