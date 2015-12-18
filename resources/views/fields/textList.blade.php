
<!-- Beginning of fields/textList.blade.php -->

<div class="{{ 'form-group '.(isset($labelSize) ? $labelSize : 'col-md-4').' text-right' }}">
    @if(isset($fieldNameText))
        <strong>{{ $fieldNameText }}</strong>
    @else
        <strong>{!! Lang::get('labels.'.$fieldName) !!}</strong>
    @endif
</div>
<div class="{{ 'form-group '.(isset($fieldSize) ? $fieldSize : 'col-md-8').' mark' }}">
    @if(isset($fieldValue) && strlen($fieldValue) > 0)
        @if(isset($urlName))
            <!-- using helper function for named route -->
            {!! link_to_route($urlName, $fieldValue, ['id' => (isset($urlID) ? $urlID : $fieldValue)]) !!}
        @else
            {{ $fieldValue }}
        @endif
    @else
        &nbsp;
    @endif
</div>

<!-- End of fields/textList.blade.php -->
