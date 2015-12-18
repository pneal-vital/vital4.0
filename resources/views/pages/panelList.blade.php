@extends('app')

@section('content')
    <!-- section('content') of pages/panelList.blade.php  -->

    <h1>
        @yield('title')
    </h1>

    <hr>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">


                    <div class="panel-heading clearfix">
                        @yield('heading')
                    </div>
                    <div class="panel-body">

                        @include('errors.list')

                        @yield('form')

                    </div>
                </div>
            </div>
        </div>
    </div>

    @yield('list')

    <!-- stop of pages/panelList.blade.php, section('content') -->
@stop
