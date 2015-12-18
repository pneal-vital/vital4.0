
<!-- Beginning of fields/checkBox.blade.php -->

<!-- {{ $fieldName }} Form Input -->
<div class="form-group {{ isset($columnSize) ? $columnSize : '' }}">
    {{-- Could not get this to work with double curly or curly bang bang
        Form::label($fieldName, Lang::get('labels.'.$fieldName), ['class' => (isset($labelSize) ? $labelSize : 'col-md-4').' control-label']) --}}
    <label for="{{ $fieldName }}" class="{{ (isset($labelSize) ? $labelSize : 'col-md-4').' control-label' }}">{!! Lang::get('labels.'.$fieldName) !!}</label>

    {{-- see page: http://demos.jquerymobile.com/1.4.3/checkboxradio-checkbox/#&ui-state=dialog  --}}
    <div class="{{ isset($fieldSize) ? $fieldSize : 'col-md-8' }}">
        <fieldset data-role="controlgroup">
            @foreach($lists as $key => $value)
                <div class="col-md-12">
                    <!-- {{ $checked = "{$fieldName}_cb_{$key}" }} -->
                    <!-- {{ $isChecked }} -->
                    <!-- {{ $checked = (strpos($isChecked, $checked) > 0) == 'on' ? 'checked' : '' }} -->
                    <input name="{{ $fieldName }}_cb_{{ $key }}" id="{{ $fieldName }}_cb_{{ $key }}" type="checkbox" {{ $checked }}>
                    <label for="{{ $fieldName }}_cb_{{ $key }}">{{ $value }}</label>
                </div>
            @endforeach
        </fieldset>
    </div>

</div>

<!-- End of fields/checkBox.blade.php -->
