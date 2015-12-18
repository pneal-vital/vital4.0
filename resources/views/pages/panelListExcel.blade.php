@extends('appExcel')

@section('content')
    <!-- section('content') of pages/panelListExcel.blade.php  -->

    <tr>
                @yield('title')
    </tr>
    <tr>
                @yield('heading')
    </tr>
    <tr>
                @yield('form')
    </tr>
                @yield('list')

    <!-- stop of pages/panelListExcel.blade.php, section('content') -->
@stop
