<!doctype html>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <!-- link rel="stylesheet" type="text/css" href="__ URL::asset('css/excel.html.css') __"/ -->
        {{-- HTML::style('css/excel.html.css') --}}
        <style type="text/css">
            h1,.h1 {
                font-size: 24.0pt;
                font-weight: 700;
                color: #333333;
                background-color: #ffffff;
                text-align: center;
            }
            h2,.h2 {
                font-size: 12.0pt;
                font-weight: 700;
                color: #333333;
                background-color: #ffffff;
                text-align: center;
            }
            h3,.h3 {
                margin-top: 0;
                margin-bottom: 0;
                font-size: 10.0pt;
                font-weight: 700;
                color: #333333;
                background-color: #ffffff;
                text-align: center;
            }
            th,.th {
                font-size: 10.0pt;
                font-weight: 700;
                color: #333333;
                background-color: #ffffff;
                text-align: center;
            }
            td,.td {
                font-size: 10.0pt;
                font-weight: 400;
                color: #333333;
                background-color: #ffffff;
            }
            .fieldName {
                font-size: 10.0pt;
                font-weight: 700;
                color: #333333;
                background-color: #ffffff;
                text-align: right;
            }
            .redFont{color:red}
        </style>

        @yield('head')
    </head>
    <body>
        <table>
            <tbody>

        @yield('content')

        @yield('footer')

            </tbody>
        </table>
    </body>
</html>

