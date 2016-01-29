@extends('pages.panel')

@section('panel')
    <!-- section('panel') of pages/rolePermissions/edit.blade.php  -->

    <div class="panel-heading">@lang('labels.titles.Edit_RolePermissions')</div>
    <div class="panel-body">

        @include('errors.list')

        {{-- Using form-model binding --}}
        {!! Form::model($rolePermission, ['class' => 'form-horizontal', 'method' => 'PATCH', 'action' => ['RolePermissionsController@update', $rolePermission['role_id']]]) !!}

        @include('fields.ddList', ['fieldName' => 'role_id', 'lists' => $roles, 'onChangeSubmit' => 'true' ])

        <div class="form-group">
            <!-- set thisLine {{ $thisLine = strpos(array_values($permissions)[0]->name,'.') > 0 ? substr(array_values($permissions)[0]->name,0,strpos(array_values($permissions)[0]->name,'.')) : array_values($permissions)[0]->name }} -->
            <label for="{!! $thisLine !!}" class="col-md-4 control-label">{!! ucfirst($thisLine) !!}: </label><div class="col-md-8">
                @foreach($permissions as $permission)
                    @if($thisLine != substr($permission->name,0,strpos($permission->name,'.')))
                        </div></div>
                        <!-- new thisLine {{ $thisLine = strpos($permission->name,'.') > 0 ? substr($permission->name,0,strpos($permission->name,'.')) : $permission->name }} -->
                        <div class="form-group"><label for="{!! $thisLine !!}" class="col-md-4 control-label">{!! ucfirst($thisLine) !!}: </label><div class="col-md-8">
                    @endif
                    <div class="col-md-12">
                        <!-- checked? {{ $checked = $permission->checked ? 'checked' : '' }} -->
                        <!-- disabled? {!! $disabled = $permission->disabled ? 'disabled="disabled"' : '' !!} -->
                        <input name="cb_{{ $permission->name }}" id="cb_{{ preg_replace("/\./","_",$permission->name) }}" type="checkbox" {{ $checked }} {!! $disabled !!} />
                        <label for="cb_{{ $permission->name }}" >{{ $permission->display_name }}</label>
                    </div>
                @endforeach
            </div>
        </div>

        @include('fields.button', ['columnSize' => 'col-md-4', 'fieldSizeOffset' => ''               , 'submitButtonName' => 'Cancel'])
        @include('fields.button', ['columnSize' => 'col-md-6', 'fieldSizeOffset' => 'col-md-offset-2', 'submitButtonName' => 'Update_RolePermissions'])

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


<!-- stop of pages/rolePermissions/edit.blade.php, section('panel') -->
@stop

