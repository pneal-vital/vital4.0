@extends('pages.panelListExcel')

@section('title')
    <!-- section('title') of pages/associateNumber/excel.blade.php  -->
    <!-- hack {{ $columnCount = count($associateNumbers[0]['attributes']) }} -->

    <td class="page-title" colspan="{{ $columnCount }}">
        <h1>
            @lang('labels.titles.AssociateNumber')
        </h1>
    </td>

    <!-- stop of pages/associateNumber/excel.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('heading') of pages/associateNumber/excel.blade.php  -->
    <!-- hack {{ $usedCount = ((count($associateNumber) - 2) * 2 > $columnCount ? $columnCount : (count($associateNumber) - 2) * 2) }} -->

    <td class="panel-title" colspan="{{ $usedCount }}">
        <h3>
            @lang('labels.titles.AssociateNumber_Filter')
        </h3>
    </td>
    @for($i = $usedCount; $i < $columnCount; $i++)
        <td></td>
    @endfor

    <!-- stop of pages/associateNumber/excel.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/associateNumber/excel.blade.php  -->

    {{-- Filter fields --}}
    <td class="fieldName">{!! Lang::get('labels.fromDate') !!}</td><td>{{ $associateNumber['fromDate'] }}</td>
    <td class="fieldName">{!! Lang::get('labels.toDate'  ) !!}</td><td>{{ $associateNumber['toDate'  ] }}</td>
    @for($i = $usedCount; $i < $columnCount; $i++)
        <td></td>
    @endfor

    <!-- stop of pages/associateNumber/excel.blade.php, section('form') -->
@stop

@section('list')
    <!-- section('list') of pages/associateNumber/excel.blade.php  -->

    <tr>
        @for($i = 0; $i < $columnCount; $i++)
            <td></td>
        @endfor
    </tr>
    {{-- Filtered list --}}
    <tr>
        <td class="th">{!! Lang::get('labels.userName')       !!}</td>
        <td class="th">{!! Lang::get('labels.receivedUnits')  !!}</td>
        <td class="th">{!! Lang::get('labels.putAwayRec')     !!}</td>
        <td class="th">{!! Lang::get('labels.putAwayRplComb') !!}</td>
        <td class="th">{!! Lang::get('labels.putAwayRplSngl') !!}</td>
        <td class="th">{!! Lang::get('labels.putAwayReserve') !!}</td>
        <td class="th">{!! Lang::get('labels.replenTotes')    !!}</td>
    </tr>

    @foreach($associateNumbers as $pt)
        <tr>
            <td>{{ $pt->userName       }}</td>
            <td>{{ $pt->receivedUnits  }}</td>
            <td>{{ $pt->putAwayRec     }}</td>
            <td>{{ $pt->putAwayRplComb }}</td>
            <td>{{ $pt->putAwayRplSngl }}</td>
            <td>{{ $pt->putAwayReserve }}</td>
            <td>{{ $pt->replenTotes    }}</td>
        </tr>
    @endforeach

    <!-- stop of pages/associateNumber/excel.blade.php, section('list') -->
@stop
