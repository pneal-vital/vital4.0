
<!-- Beginning of fields/textEntry.blade.php -->

<!-- {{ $fieldName }} Form Input -->
<div class="form-group {{ isset($columnSize) ? $columnSize : '' }}">
    {{-- Could not get this to work with double curly or curly bang bang
        Form::label($fieldName, Lang::get('labels.'.$fieldName), ['class' => (isset($labelSize) ? $labelSize : 'col-md-4').' control-label']) --}}
    <label for="{{ $fieldName }}" class="{{ (isset($labelSize) ? $labelSize : 'col-md-4').' control-label' }}">{!! Lang::get('labels.'.$fieldName) !!}</label>
    <div class="{{ isset($fieldSize) ? $fieldSize : 'col-md-8' }}">
        {!! Form::text($fieldName, null, ['class' => 'form-control', 'placeholder' => Lang::get('labels.'.(isset($labelType) ? $labelType : 'enter').'.'.$fieldName) ]) !!}
        @if($errors->has($fieldName))
            <ul class="alert alert-danger">
                @foreach($errors->get($fieldName) as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
    </div>
</div>

<!-- End of fields/textEntry.blade.php -->
