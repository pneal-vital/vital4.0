
<!-- Beginning of fields/dateEntry.blade.php -->

{{-- see: http://xdsoft.net/jqplugins/datetimepicker/
     and: http://stackoverflow.com/questions/15232600/laravel-stylesheets-and-javascript-dont-load-for-non-base-routes --}}

<!-- {{ $fieldName }} Form Input -->
<div class="form-group {{ isset($columnSize) ? $columnSize : '' }}">
    {!! Form::label($fieldName, Lang::get('labels.'.$fieldName), ['class' => (isset($labelSize) ? $labelSize : 'col-md-4').' control-label']) !!}
    <div class="{{ isset($fieldSize) ? $fieldSize : 'col-md-8' }}">
        {!! Form::text($fieldName, null, ['id' => $fieldName, 'class' => 'form-control', 'placeholder' => Lang::get('labels.'.(isset($labelType) ? $labelType : 'enter').'.'.$fieldName) ]) !!}
        @if($errors->has($fieldName))
            <ul class="alert alert-danger">
                @foreach($errors->get($fieldName) as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
    </div>
</div>

<script>
    $({!! "'#$fieldName'" !!}).datetimepicker({
        validateOnBlur:{!! isset($validateOnBlur) ? $validateOnBlur : "true" !!},
        format:{!! isset($fieldFormat) ? "'".$fieldFormat."'" : "'Y-m-d H:i:s'" !!},
@if(isset($onChangeSubmit) && $onChangeSubmit)
        onSelectTime:function(){
            //alert('onSelectTime fired');
            $({!! "'#$fieldName'" !!}).closest("form").submit();
        }
@endif
    });
</script>

<!-- End of fields/dateEntry.blade.php -->
