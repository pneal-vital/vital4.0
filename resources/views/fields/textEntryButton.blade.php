
<!-- Beginning of fields/textEntryButton.blade.php -->

<!-- {{ $fieldName }} Form Input -->
<div class="form-group">
    {{-- Could not get this to work with double curly or curly bang bang
        Form::label($fieldName, Lang::get('labels.'.$fieldName), ['class' => (isset($labelSize) ? $labelSize : 'col-md-2').' control-label']) --}}
    <label for="{{ $fieldName }}" class="{{ (isset($labelSize) ? $labelSize : 'col-md-2').' control-label' }}">{!! Lang::get('labels.'.$fieldName) !!}</label>
    <div class="{{ isset($fieldSize) ? $fieldSize : 'col-sm-5' }}">
        {!! Form::text($fieldName, null, ['class' => 'form-control', 'placeholder' => Lang::get('labels.'.(isset($labelType) ? $labelType : 'enter').'.'.$fieldName) ]) !!}
    </div>
    <div class="{{ isset($buttonSize) ? $buttonSize : 'col-sm-5' }}">
        {{-- _button type="button" class="btn btn-primary btn-block"_@lang('labels.buttons.Select_'.$fieldName)_/button_ --}}
        {!! Form::submit( \Lang::get((isset($buttonTitle) ? $buttonTitle : 'labels.buttons.Select_'.$fieldName)), ['id' => 'btn-'.$fieldName, 'name' => 'btn_'.$fieldName, 'class' => 'btn '.(isset($quickReceive->$fieldName) && strlen($quickReceive->$fieldName) > 0 ? 'btn-success' : 'btn-primary').' btn-block']) !!}
    </div>
    @if($errors->has($fieldName))
        <ul class="alert alert-danger">
            @foreach($errors->get($fieldName) as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
</div>

<!-- End of fields/textEntryButton.blade.php -->
