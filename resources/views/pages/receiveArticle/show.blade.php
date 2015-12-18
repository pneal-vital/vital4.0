@extends('app')

@section('head')
<!-- section('head') of pages/receiveArticle/show.blade.php  -->

{{-- meta name="csrf-token" content="{{ csrf_token() }}" / --}}
<meta name="csrf-token" content="{{ $article->objectID }}" />

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<script>
    // A $( document ).ready() block.
    $( document ).ready(function() {
        console.log( "ready!" );

        {{--
        // Log the TR elements with thier ID attribute
        //DOM ELEMENTS
        $("tr").each(function(index, value) {
            console.log('tr' + index + ':' + $(this).attr('id'));
        });

        $("form").each(function(index, value) {
            console.log('form' + index + ':' + $(this).attr('id'));
        });

        $("form input[type=submit]").each(function(index, value) {
            console.log('form input[type=submit]' + index + ':' + $(this).attr('id'));
        });
        --}}

    });

    {{-- got alternative to work using  var buttonID = event.originalEvent.explicitOriginalTarget.id;  see below
    $("form input[type=submit]").click(function() {
        alert('found ' + $(this).attr('id'));
    });
    --}}

    {{--
        Mark an input[type=submit] as clicked.
        see: http://stackoverflow.com/questions/5721724/jquery-how-to-get-which-button-was-clicked-upon-form-submission
    $('#form-UPC-grid').on('click', '#btn-add-setting', function() {
        console.log('form input[type="submit"].click(..), ' + $(this).attr('id') + ' ' + $(this).attr('clicked'));
        $('input[type="submit"]', $(this).parents("form")).removeAttr("clicked");
        $(this).attr("clicked", "true");
        console.log('form input[type="submit"].click(..), ' + $(this).attr('id') + ' ' + $(this).attr('clicked'));
    });
    --}}

    {{--
        A $( document ) .on 'submit' of the form with id="form-UPC-grid", run unnamed function
        see: http://api.jquery.com/on/#on-events-selector-data
    --}}
    $( document ).on('submit', 'form[id="form-UPC-grid"]', function(event) {
        console.log( "form-UPC-grid, on submit!" );
        event.preventDefault();

        {{-- see: http://laravel.io/forum/02-06-2015-ajax-code-works-in-laravel-4-but-not-in-laravel-5 --}}
        var url = "{!! route('receiveArticle.index') . '/' . $purchaseOrderDetail->objectID . '/refresh' !!}";
        console.log( "form-UPC-grid, url: ", url );

        {{-- see: http://api.jquery.com/jquery.ajax/ --}}
        $.ajax({
            url: url,
            type: 'POST',
            data: $(this).closest('form').serialize(),

            success: function(data) {
                // Success...
                console.log( "Success!" );
                // UPCs grid
                $.each(data.upcs, function(index, value) {
                    // console.log('data.upcs.'+index, value);
                    $('tr[id="ug-'+index+'"]').attr("class", value.status);
                    $('tr[id="ug-'+index+'"] > td[id="ug-3"]').text( (value.receivedUnitQuantity !== 0 ? value.receivedUnitQuantity : '') );
                    $('tr[id="ug-'+index+'"] > td[id="ug-4"]').text( (value.outstandingUnitQuantity !== 0 ? value.outstandingUnitQuantity : '') );
                    $('tr[id="ug-'+index+'"] > td[id="ug-5"]').text(value.tote);
                    $('tr[id="ug-'+index+'"] > td[id="ug-6"]').text(value.toteLocation);
                });
                // refresh the $receiptHistory lines
                $.each(data.receiptHistories, function(index, value) {
                    console.log('data.receiptHistories.'+index, value);
                    // TODO Refresh the $receiptHistory lines correctly, break on ' - '
                    var values = value.Activity.split(' - ');
                    $('div[id="receiptHistories"] h5[id="rh-'+index+'"]').text(values[0]);
                    $('div[id="receiptHistories"] p[id="rh-'+index+'"]').text(values[1]);
                });
            },

            error: function(data) {
                console.log( "Error!" );
                // Error...
                var errors = data.responseJSON;

                console.log(errors);

                $.each(errors, function(index, value) {
                    $.gritter.add({
                        title: 'Error',
                        text: value
                    });
                });
            }
        });
    });

    {{--
        A $( document ) .on 'submit' of the form with id="form-texting", run unnamed function
        see: http://api.jquery.com/on/#on-events-selector-data
    --}}
    $( document ).on('submit', 'form[id="form-texting"]', function(event) {
        console.log( "form-texting, on submit!" );
        event.preventDefault();

        {{-- see: http://laravel.io/forum/02-06-2015-ajax-code-works-in-laravel-4-but-not-in-laravel-5 --}}
        var url = "{!! route('receiveArticle.index') . '/' . $purchaseOrderDetail->objectID . '/texting' !!}";
        console.log( "form-texting, url: ", url );

        {{-- see: http://stackoverflow.com/questions/5721724/jquery-how-to-get-which-button-was-clicked-upon-form-submission
        var val = $("input[type=submit][clicked=true]").val();
        console.log( "form-texting, val: ", val );
        --}}

        {{-- see: http://stackoverflow.com/questions/5732555/determining-which-submit-button-was-clicked-from-jquery-javascript
             replaces  data: $(this).closest('form').serialize()
         --}}
        // var the_form = jQuery(this).parents("form");
        var the_form = jQuery(this).closest("form");
        var data = the_form.serialize();
        //var button = event.target;
        //data = data + "&" + button.name + "=" + button.value;
        //console.log( "form-texting, button: " + button.name + "=" + button.value );
        var buttonID = event.originalEvent.explicitOriginalTarget.id;
        var buttonValue = event.originalEvent.explicitOriginalTarget.value;
        console.log( "form-texting, buttonID: " + buttonID + ", buttonValue: " + buttonValue );
        data = data + "&clicked=" + buttonID;

        {{-- see: http://api.jquery.com/jquery.ajax/ --}}
        $.ajax({
            url: url,
            type: 'POST',
            data: data,

            success: function(data) {
                // Success...
                console.log( "Success!" );
                // userConversations...
                $.each(data.userConversations, function(index, value) {
                    console.log('data.userConversations.'+index, value);
                    $('p[id="uc-'+index+'"]').attr("class", value.klass);
                    $('p[id="uc-'+index+'"] > span').attr("style", 'color:'+value.color);
                    $('p[id="uc-'+index+'"] > span').text(value.Text);
                });
                // blank out text entry input field
                $('input[id="text_entry"]').val('');
                // Submit the form-UPC-grid form if told to
                if(data.responseText['receipt'] == 'success') {
                    //alert('Submit the form: form-UPC-grid');
                    //See: http://blog.igeek.info/2013/using-ajax-in-laravel/
                    //fire the form submit event here
                    jQuery( '#form-UPC-grid' ).trigger( 'submit' );
                }
            },

            error: function(data) {
                console.log( "Error!" );
                // TODO Verify this does something useful
                // Error...
                var errors = data.responseJSON;

                console.log(errors);

                $.each(errors, function(index, value) {
                    $.gritter.add({
                        title: 'Error',
                        text: value
                    });
                });
            }
        });
    });
</script>

<!-- stop of pages/receiveArticle/show.blade.php, section('head') -->
@stop


@section('content')
<!-- section('content') of pages/receiveArticle/show.blade.php  -->

<div class="container-fluid">
    <h1>@lang('labels.titles.Receive_Article')</h1>
    <hr>

    <!-- Article # row -->
    <div id="articleRow" class="row form-horizontal">
        <div class="col-sm-1">
            <b>@lang('labels.Article_Number'):</b>
        </div>
        <div class="col-sm-2">
            {{ $article->objectID }}
        </div>
        <div class="col-sm-1">
            <b>@lang('labels.Description'):</b>
        </div>
        <div class="col-sm-4">
            {{ $article->Description }}
        </div>
        <div class="col-sm-2">
            {!! Form::label('rework', Lang::get('labels.Rework_Type'), ['class' => 'control-label']) !!}
        </div>
        @if(is_null($article->rework))
            <div class="form_group col-sm-2">
                {!! Form::text('rework', null, ['class' => 'form-control', 'placeholder' => Lang::get('labels.enter.rework') ]) !!}
                @if($errors->has($fieldName))
                    <ul class="alert alert-danger">
                        @foreach($errors->get($fieldName) as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @else
            <div class="form-group col-sm-2">
                <div class="form-control">
                    {{ $article->rework }}
                </div>
            </div>
        @endif
    </div>

    <!-- Assigned To row -->
    <div id="assignedToRow" class="row form-group">
        <div class="col-sm-2">
            <b>@lang('labels.Assigned_To'):</b>
        </div>
        <div class="col-sm-3">
            {{ \Auth::user()->name }}
        </div>
        <div class="col-sm-2">
            <b>@lang('labels.Cases_Expected'):</b>
        </div>
        <div class="col-sm-2">
            {{ $purchaseOrderDetail->Expected_Qty }}
        </div>
        <div class="col-sm-1">
            <b>@lang('labels.PO_Number'):</b>
        </div>
        <div class="col-sm-2">
            {{ $purchaseOrderDetail->Order_Number }}
        </div>
    </div>

    <!-- UPCs grid lines -->
    <div id="upcGridLines" class="row">
        <div class="col-sm-9 ajax-refresh">

            {!! Form::open(array('id' => 'form-UPC-grid')) !!}

            <table class="table table-condensed table-bordered table-hover">
                <thead>
                    <tr id="thead_tr_0">
                        <th class="text-center">@lang('labels.th.UPC_Number')</th>
                        <th>@lang('labels.th.Case_Unit_Quantity')</th>
                        <th>@lang('labels.th.Expected_Unit_Quantity')</th>
                        <th>@lang('labels.th.Received_Unit_Quantity')</th>
                        <th>@lang('labels.th.Outstanding_Unit_Quantity')</th>
                        <th class="text-center">@lang('labels.th.Open_Tote_Number')</th>
                        <th class="text-center">@lang('labels.th.Open_Tote_Location')</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($upcs as $index => $upc)

                        <tr class="{{ $upc['status'] }}" id="ug-{{ $index }}">
                            <td class="text-center" id="ug-0">{{ $upc['upc']                  }}</td>
                            <td class="text-right"  id="ug-1">{{ $upc['caseUnitQuantity']     }}</td>
                            <td class="text-right"  id="ug-2">{{ $upc['expectedUnitQuantity'] }}</td>
                            <td class="text-right"  id="ug-3">{{ $upc['receivedUnitQuantity'] ? $upc['receivedUnitQuantity'] : '' }}</td>
                            <td class="text-right"  id="ug-4">{{ $upc['outstandingUnitQuantity'] ? $upc['outstandingUnitQuantity'] : '' }}</td>
                            <td class="text-center" id="ug-5">{{ $upc['tote']                 }}</td>
                            <td class="text-center" id="ug-6">{{ $upc['location']             }}</td>
                        </tr>

                    @endforeach

                </tbody>
            </table>

            @include('pages.receiveArticle.tasks')

            {{--
            {!! Form::label( 'setting_name', 'Setting Name:' ) !!}
            {!! Form::text( 'setting_name', '', array(
                'id' => 'setting_name',
                'placeholder' => 'Enter Setting Name',
                'maxlength' => 20,
                'required' => true,
            ) ) !!}
            {!! Form::label( 'setting_value', 'Setting Value:' ) !!}
            {!! Form::text( 'setting_value', '', array(
                'id' => 'setting_value',
                'placeholder' => 'Enter Setting Value',
                'maxlength' => 30,
                'required' => true,
            ) ) !!}

            {!! Form::submit( 'Add Setting', array(
                'id' => 'btn-add-setting',
            ) ) !!}
            --}}

            {!! Form::close() !!}

        </div>

        <div class="col-sm-3">
            @include('pages.quickReceive.texting')
        </div>
    </div>

    {!! Form::model($purchaseOrderDetail, ['class' => 'form-horizontal', 'method' => 'patch', 'action' => 'Receive\ReceiveArticleController@filter']) !!}
    <input id="btn-enter" name="btn_enter" style="display: none;" type="submit" value=" ">

    <!-- Buttons row -->
    <div id="buttonsRow" class="row form-group">
        <div class="col-sm-4">
            {{-- _button type="button" class="btn btn-primary btn-block"_@lang('labels.buttons.Leave_Article_Receiving')_/button_ --}}
            {!! Form::submit( \Lang::get('labels.buttons.Leave_Article_Receiving'), ['id' => 'btn-leave', 'name' => 'btn_leave', 'class' => 'btn btn-primary btn-block']) !!}
        </div>
        <div class="col-sm-4">
            &nbsp;
            {{--
            <button type="button" class="btn btn-primary btn-block">@lang('labels.buttons.Close_Tote')</button>
             --}}
        </div>
        <div class="col-sm-4">
            {{-- _button type="button" class="btn btn-primary btn-block"_@lang('labels.buttons.Review_History')_/button_ --}}
            {!! Form::submit( \Lang::get('labels.buttons.Review_History'), ['id' => 'btn-history', 'name' => 'btn_history', 'class' => 'btn btn-primary btn-block']) !!}
        </div>
    </div>

    {!! Form::close() !!}

</div>

<!-- stop of pages/receiveArticle/show.blade.php, section('content') -->
@stop
