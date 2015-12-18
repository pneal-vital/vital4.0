@extends('app')

@section('content')
    <!-- section('content') of pages/panel.blade.php  -->

    @yield('pre-panel')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">

                    @yield('panel')

                </div>
            </div>
        </div>
    </div>

    @yield('post-panel')

    <!-- stop of pages/panel.blade.php, section('content') -->
@stop
