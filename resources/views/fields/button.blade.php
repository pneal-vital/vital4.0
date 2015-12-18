<!-- Beginning of fields/button.blade.php -->

<!-- {{ $submitButtonText = (isset($submitButtonText) ? $submitButtonText : Lang::get('labels.buttons.'.$submitButtonName)) }} Form Button -->
<div class="form-group {{ isset($columnSize) ? $columnSize : '' }}">
    <div class="{{ isset($fieldSizeOffset) ? $fieldSizeOffset : 'col-md-6 col-md-offset-4' }}">
        {!! Form::submit($submitButtonText, ['id' => 'btn-'.$submitButtonName, 'name' => 'btn_'.$submitButtonName, 'class' => 'btn '.(isset($submitButtonClass) ? submitButtonClass : 'btn-primary').' form-control']) !!}
    </div>
</div>
<!-- End of fields/button.blade.php -->

<!-- TODO replace generated _input above with this button for search, filter, ..
_button type="button" class="btn btn-info">
    _span class="glyphicon glyphicon-search">_/span> Search
_/button>
 -->