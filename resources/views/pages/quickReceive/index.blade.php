@extends('app')

@section('head')
<!-- section('head') of pages/quickReceive/index.blade.php  -->

<meta name="csrf-token" content="{{ csrf_token() }}" />
{{-- meta name="csrf-token" content="{{ $article->objectID }}" / --}}

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<script>
    // A $( document ).ready() block.
    $( document ).ready(function() {
        console.log( "ready!" );
    });
    {{--
        // Passing a named function instead of an anonymous function.
        function readyFn( jQuery ) {
            console.log( "readyFn!" );
        }

        $( document ).ready( readyFn );
    --}}
</script>

<!-- stop of pages/quickReceive/index.blade.php, section('head') -->
@stop


@section('content')
<!-- section('content') of pages/quickReceive/index.blade.php  -->

<div class="container-fluid">
    <h1>@lang('labels.titles.Quick_Receive')</h1>
    <hr>

    <div id="form_side" class="col-sm-7 form-group">

        @include('pages.quickReceive.form')

        <hr>

        @include('pages.quickReceive.upcGridLines')

        @include('pages.quickReceive.pickFaceLines')

    </div>
    <div class="col-sm-5">
        @include('pages.quickReceive.texting')
    </div>

</div>

<!-- stop of pages/quickReceive/index.blade.php, section('content') -->
@stop
