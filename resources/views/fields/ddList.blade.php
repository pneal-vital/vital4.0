
<!-- Beginning of fields/ddList.blade.php -->

<!-- {{ $fieldName }} Form Input -->
<div class="form-group">
    {{-- Could not get this to work with double curly or curly bang bang
        Form::label($fieldName, Lang::get('labels.'.$fieldName), ['class' => (isset($labelSize) ? $labelSize : 'col-md-4').' control-label']) --}}
    <label for="{{ $fieldName }}" class="{{ (isset($labelSize) ? $labelSize : 'col-md-4').' control-label' }}">{!! Lang::get('labels.'.$fieldName) !!}</label>
    <div class="{{ (isset($fieldSize) ? $fieldSize : 'col-md-8') }}">
        @if(isset($lists) && ( count($lists) > 2 || (count($lists) == 2 && !isset($lists['0'])) ))
            {!! Form::select($fieldName, $lists, null, (isset($onChangeSubmit) && $onChangeSubmit ? ['onchange' => 'this.form.submit()'] : [])) !!}
        @else
            <div class="form-control">
                @if(Form::getValueAttribute($fieldName) != null)
                    @if(isset($lists) && isset($lists[Form::getValueAttribute($fieldName)]))
                        {!! $lists[Form::getValueAttribute($fieldName)] !!}
                        {!! Form::hidden($fieldName,Form::getValueAttribute($fieldName)) !!}
                    @else
                        {!! Form::getValueAttribute($fieldName) !!}
                        {!! Form::hidden($fieldName,Form::getValueAttribute($fieldName)) !!}
                    @endif
                @elseif(isset($lists) && count($lists) > 0)
                    {!! $lists[array_keys($lists)[count($lists)-1]] !!}
                    {!! Form::hidden($fieldName,array_keys($lists)[count($lists)-1]) !!}
                @else
                    {!! Form::text($fieldName, null, ['class' => 'form-control', 'placeholder' => Lang::get('labels.'.(isset($labelType) ? $labelType : 'enter').'.'.$fieldName) ]) !!}
                @endif
            </div>
        @endif
        @if($errors->has($fieldName))
            <ul class="alert alert-danger">
                @foreach($errors->get($fieldName) as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
    </div>
</div>

<!-- End of fields/ddList.blade.php -->
