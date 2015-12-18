
<!-- Beginning of navbar/guest.blade.php -->

{{-- in here is_null(\Auth::user()) == true --}}

<nav class="navbar">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ URL::route('home') }}"><b>VITaL 4.0</b></a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav nav-pills navbar-nav">
                <li>{!! link_to_route('home', Lang::get('labels.navbar.Home')) !!}</li>
            </ul>
            <ul class="nav nav-pills navbar-nav navbar-right">
                <li><a href="/auth/login"><span class="glyphicon glyphicon-log-in"></span>&nbsp; @lang('labels.navbar.Login')</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- End of navbar/guest.blade.php -->
