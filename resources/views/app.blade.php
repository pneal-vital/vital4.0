<!doctype html>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>VITaL 4.0</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
        <script src="https://code.jquery.com/jquery.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

        {{-- see: http://xdsoft.net/jqplugins/datetimepicker/
             and: http://stackoverflow.com/questions/15232600/laravel-stylesheets-and-javascript-dont-load-for-non-base-routes --}}
        {{-- _link rel="stylesheet" type="text/css" href="jquery.datetimepicker.css"/_ --}}
        {{-- _script src="jquery.js"__/script_ --}}
        {{-- _script src="jquery.datetimepicker.js"__/script_ --}}
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/jquery.datetimepicker.css') }}"/>
        <script src="{{ URL::asset('js/jquery.datetimepicker.js') }}"></script>

        @yield('head')
    </head>
    <body>

        <!-- nav bar -->
        @if(is_null(\Auth::user()))
            @include('navbar.guest')
        @else
            @include('navbar.user')
        @endif

        <div class="container">
            {{-- replaced with Laracasts Flash
            @include('pages.flash')
             (below) include from package flash :: the message.blade.php
             --}}
            {{-- _include('flash::message') --}}
            @if(Session::has('flash_notification.message'))
                <div class="alert alert-{{ Session::get('flash_notification.level') }}">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

                    {{ Session::get('flash_notification.message') }}
                </div>
            @endif

            @yield('content')
        </div>

        <script>
            $('#flash-overlay-modal').modal();
            {{-- script to delay 3 seconds and rollup flash_messages (without flash_messages_important) --}}
//            $('div.alert').not('.alert-important').delay(3000).slideUp(300);
            {{-- $('div.alert-info').delay(3000).slideUp(300); --}}
        </script>

        <div class="container">
            @yield('footer')
        </div>
    </body>
</html>

