@extends('pages.panel')

@section('panel')
    <!-- section('panel') of pages/userRoles/edit.blade.php  -->

    <div class="panel-heading">@lang('labels.titles.Edit_UserRoles')</div>
    <div class="panel-body">

        @include('errors.list')

        {{-- Using form-model binding --}}
        {!! Form::model($userRole, ['class' => 'form-horizontal', 'method' => 'PATCH', 'action' => ['UserRolesController@update', $userRole['user_id']]]) !!}

        <div class="form-group">
            <label for="User" class="col-md-4 control-label">@lang('labels.titles.User'): </label>
            <div class="col-md-6 mark">{{ $user->name.', '.$user->email }}</div>
            <div class="col-md-2">&nbsp;</div>
        </div>

        <div class="form-group">
            <!-- set thisLine {{ $thisLine = ' ' }} -->
            <label for="{!! $thisLine !!}" class="col-md-4 control-label">{!! ucfirst($thisLine) !!}</label><div class="col-md-8">
                @foreach($rNames as $role)
                    @if($thisLine != substr($role->name,0,strpos($role->name,'.')))
                        </div></div>
                        <!-- new thisLine {{ $thisLine = strpos($role->name,'.') > 0 ? substr($role->name,0,strpos($role->name,'.')) : $role->name }} -->
                        <div class="form-group"><label for="{!! $thisLine !!}" class="col-md-4 control-label">{!! ucfirst($thisLine) !!}: </label><div class="col-md-8">
                    @endif
                    <div class="col-md-12">
                        <!-- checked? {{ $checked = $role->checked ? 'checked' : '' }} -->
                        <!-- disabled? {!! $disabled = $role->disabled ? 'disabled="disabled"' : '' !!} -->
                        <input name="cb_{{ $role->name }}" id="cb_{{ preg_replace("/\./","_",$role->name) }}" type="checkbox" {{ $checked }} {!! $disabled !!} />
                        <label for="cb_{{ $role->name }}" >{{ $role->display_name }}</label>
                    </div>
                @endforeach
            </div>
        </div>

        @include('fields.button', ['columnSize' => 'col-md-4', 'fieldSizeOffset' => ''               , 'submitButtonName' => 'Cancel'])
        @include('fields.button', ['columnSize' => 'col-md-6', 'fieldSizeOffset' => 'col-md-offset-2', 'submitButtonName' => 'Update_UserRoles'])

        {!! Form::close() !!}

    </div>



<script>
    $( "input[id$=_view]" ).each(function() {
        $(this).on("click", function() {
            var subid = this.id.replace(/_view/, "");
            var checkBoxes = $('input[id^=' + subid + ']');
            for(var key in checkBoxes) {
                if(checkBoxes[key] != this) {
                    checkBoxes[key].disabled = !this.checked;
                    if(!this.checked) {
                        checkBoxes[key].checked = false;
                    }
                }
            }
        }).change();
    });
</script>


<!-- stop of pages/userRoles/edit.blade.php, section('panel') -->
@stop

