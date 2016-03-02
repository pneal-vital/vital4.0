<!-- Beginning of pages/quickReceive/form.blade.php  -->

@if($errors->any())
    <ul class="alert alert-danger">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

{!! Form::model($quickReceive, ['class' => 'form-horizontal', 'method' => 'patch', 'action' => 'Receive\QuickReceiveController@filter']) !!}
<input id="btn-enter" name="btn_enter" style="display: none;" type="submit" value=" ">

<!-- Work Table row -->
<div id="Work_Table_row" class="form-horizontal">
    @include('fields.textEntryButton', ['fieldName' => 'Work_Table'])
</div>

@if(isset($locations))
    @include('pages.location.list', ['route' => 'quickReceive.show'])
@endif

<!-- PurchaseOrder row -->
<div id="PurchaseOrder_row" class="form-horizontal">
    @include('fields.textEntryButton', ['fieldName' => 'Purchase_Order'])
</div>

@if(isset($purchaseOrders))
    @include('pages.purchaseOrder.list', ['route' => 'quickReceive.show'])
@endif

<!-- Article row -->
<div id="Article_row" class="form-horizontal">
    @if(isset($quickReceive->Purchase_Order))
        @include('fields.textEntryButton', ['fieldName' => 'Article'])
    @else
        @include('fields.textEntry', ['fieldName' => 'Article', 'labelSize' => 'col-sm-2', 'fieldSize' => 'col-sm-5'])
    @endif
</div>

@if(isset($receiveArticles))
    @include('pages.receiveArticle.list', ['route' => 'quickReceive.show'])
@endif

<!-- UPC row -->
<div id="UPC_row" class="form-horizontal">
    @if(isset($quickReceive->Article) && strlen($quickReceive->Article) > 0)
        <br><br>
    @else
        @include('fields.textEntry', ['fieldName' => 'UPC', 'labelSize' => 'col-sm-2', 'fieldSize' => 'col-sm-5'])
        {{-- _include('fields.textEntryButton', ['fieldName' => 'UPC', 'buttonTitle' => 'labels.buttons.scanned_UPC']) --}}
    @endif
</div>

<!-- Rework row -->
<div id="Rework_row" class="form-horizontal">
    @if(!isset($quickReceive->Article) || strlen($quickReceive->Article) == 0)
        <br><br>
    @elseif(isset($quickReceive->Rework) && strlen($quickReceive->Rework) > 0)
        <div class="form-group">
            {!! Form::label('Rework', Lang::get('labels.Rework'), ['class' => 'col-sm-2 control-label']) !!}
            <input name="Rework" type="hidden" value="{{ $quickReceive->Rework }}">
            <div class="col-sm-5">
                <div class="form-control">
                    {{ $quickReceive->Rework }}
                </div>
            </div>
            @if(isset($quickReceive->Comingled) && strlen($quickReceive->Comingled) > 0)
                <div class="col-sm-5">
                    <div class="text-center">
                        <span style="color:darkBlue; background:gold">
                            <Strong> {{ $quickReceive->Comingled }} </Strong>
                        </span>
                    </div>
                </div>
            @endif
        </div>
    @else
        {{-- _include('fields.textEntryButton', ['fieldName' => 'Rework']) --}}
        @include('fields.ddList', ['fieldName' => 'Rework', 'lists' => array_merge(['' => Lang::get('labels.enter.rework')], $reworks), 'labelSize' => 'col-sm-2', 'fieldSize' => 'col-sm-5', 'onChangeSubmit' => 'true' ])
    @endif
</div>

<hr>

<!-- Buttons row -->
<div id="buttonsRows" class="row form-group">
    <div class="col-sm-6">
        {!! Form::submit( \Lang::get('labels.buttons.Leave_Quick_Receiving'), ['id' => 'btn-leave', 'name' => 'btn_leave', 'class' => 'btn btn-primary btn-block']) !!}
    </div>
    <div class="col-sm-6">
        @if(Entrust::hasRole(['super','manager','support']) && Session::has('alreadyInUse'))
            {!! Form::submit( \Lang::get('labels.buttons.Override_AlreadyInUse'), ['id' => 'btn-alreadyInUse', 'name' => 'btn_alreadyInUse', 'class' => 'btn btn-primary btn-block']) !!}
        @else
            &nbsp;
        @endif
    </div>
</div>

{!! Form::close() !!}

<!-- End of pages/quickReceive/form.blade.php -->
