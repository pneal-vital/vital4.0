<!-- Beginning of pages/quickReceive/tasks.blade.php  -->

<hr>

<b>Last Tasks Completed</b>
<div id="receiptHistories" class="list-group">

    @if(isset($receiptHistories))
        @foreach($receiptHistories as $index => $receiptHistory)
            <a href="#" class="list-group-item">
                {{-- TODO laravel blade escaping - Below does not seam to work, should not escape the html strings!
                     See: https://laracasts.com/discuss/channels/general-discussion/blade-content-escaping
                <h5 class="list-group-item-heading">{{{ str_replace(' - ','</h5><p class="list-group-item-text">', $receiptHistory->Activity) }}}</p>
                <h5 class="list-group-item-heading">{{{ str_replace(' - ','</h5><p class="list-group-item-text">', $receiptHistory->Activity) }}}</p>
                --}}
                @for($i = 0; $i < count(($lines = explode(' - ', $receiptHistory->Activity))); $i++)
                    @if($i == 0)
                        <h5 id="rh-{{ $index }}" class="list-group-item-heading">{{ $lines[$i] }}</h5>
                    @else
                        <p id="rh-{{ $index }}" class="list-group-item-text">{{ $lines[$i] }}</p>
                    @endif
                @endfor
            </a>
        @endforeach
    @endif

</div>

<!-- End of pages/quickReceive/tasks.blade.php -->
