<!-- Beginning of pages/flash.blade.php  -->

    @if(Session::has('flash_message'))
        <div class="alert alert-success {{ Session::has('flash_message_important') ? 'alert-important' : '' }}">
            @if(Session::has('flash_message_important'))
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            @endif

            {{-- session('..') written this way assumes Session::get('..') --}}
            {{ session('flash_message') }}
        </div>
    @endif

<!-- End of pages/flash.blade.php -->
