<!-- Beginning of pages/quickReceive/texting.blade.php  -->

<script>
    {{--
        A $( document ) .on 'submit' of the form with id="form-texting", run unnamed function
        see: http://api.jquery.com/on/#on-events-selector-data
    --}}
    $( document ).on('submit', 'form[id="form-texting"]', function(event) {
        console.log( "form-texting, on submit!" );
        event.preventDefault();

{{-- see: http://laravel.io/forum/02-06-2015-ajax-code-works-in-laravel-4-but-not-in-laravel-5 --}}
        var url = "{!! route('quickReceive.texting') !!}";
        console.log( "form-texting, url: ", url );

{{-- see: http://stackoverflow.com/questions/5721724/jquery-how-to-get-which-button-was-clicked-upon-form-submission
     and: http://stackoverflow.com/questions/5732555/determining-which-submit-button-was-clicked-from-jquery-javascript
--}}
        // var the_form = jQuery(this).parents("form");
        var the_form = jQuery(this).closest("form");
        var data = the_form.serialize();

        //TODO next line is Firefox Specific, need a browser safe way to know what button was pressed
        //  google: Alternative to explicitOriginalTarget.id for chrome and IE 8
        //          Jquery ajax call click event submit button
        //          JavaScript - onClick to get the ID of the clicked button
        //var buttonID = event.originalEvent.explicitOriginalTarget.id;
        //var buttonValue = event.originalEvent.explicitOriginalTarget.value;
        // from: How can I get the button that caused the submit from the form submit event?
        var $btn = $(document.activeElement);

        if (
            /* there is an activeElement at all */
            $btn.length &&

            /* it's a child of the form */
            the_form.has($btn) &&

            /* it's really a submit element */
            $btn.is('button[type="submit"], input[type="submit"], input[type="image"]') &&

            /* it has a "name" attribute */
            $btn.is('[name]')
        ) {
            console.log("Seems, that this element was clicked:", $btn);
            /* access $btn.attr("name") and $btn.val() for data */
            var buttonID = $btn.attr("id");
            var buttonValue = $btn.attr("name");
        } else {
            var buttonID = "text_entry";
            var buttonValue = "text_entry";
        }

        console.log( "form-texting, buttonID: " + buttonID + ", buttonValue: " + buttonValue );
        data = data + "&clicked=" + buttonID;

{{-- see: http://api.jquery.com/jquery.ajax/ --}}
        $.ajax({
            url: url,
            type: 'POST',
            data: data,

            success: function(data) {
                // Success...
                console.log( "Texting success!" );
                // userConversations...
                $.each(data.userConversations, function(index, value) {
                    console.log('data.userConversations.'+index, value);
                    $('p[id="uc-'+index+'"]').attr("class", value.klass);
                    $('p[id="uc-'+index+'"] > span').attr("style", 'color:'+value.color);
                    $('p[id="uc-'+index+'"] > span').html( (value.Text.length ? value.Text : '&nbsp') );
                });
                // blank out text entry input field
                $('input[id="text_entry"]').val('');
                // Toggle default/success on Receive_UPC & Close_Tote buttons
                if(data.responseText['mode'] == 'Receive_UPC') {
                    var $btn = $('#form-texting #btn-receive-upc[class~="btn-default"]');
                    if($btn.length) handle_ReceiveUPC_onClick.call($btn);
                } else if(data.responseText['mode'] == 'Close_Tote') {
                    // $('#form-texting #btn-close-tote[class~="btn-default"]').call('handle_CloseTote_onClick');
                    var $btn = $('#form-texting #btn-close-tote[class~="btn-default"]');
                    if($btn.length) handle_CloseTote_onClick.call($btn);
                } else {
                    //alert(data.responseText['mode']);
                    $('#form-texting #btn-receive-upc[class~="btn-success"]').toggleClass('btn-default btn-success');
                    $('#form-texting #btn-close-tote[class~="btn-success"]').toggleClass('btn-default btn-success');
                }
                // Submit the form-UPC-grid form if told to
                console.log( "data.responseText[receipt]: "+data.responseText['receipt'] );
                if(data.responseText['receipt'] == 'refresh') {
                    //alert('Submit the form: form-UPC-grid');
                    //See: http://blog.igeek.info/2013/using-ajax-in-laravel/
                    //fire the form submit event here
                    jQuery( '#form-UPC-grid' ).trigger( 'submit' );
                }
                // Submit the form-pick-face form if told to
                console.log( "data.responseText[closeTote]: "+data.responseText['closeTote'] );
                //if(typeof data.responseText['closeTote'] != "undefined" && data.responseText['closeTote'] == 'success') {
                if(data.responseText['closeTote'] == 'refresh') {
                    jQuery( '#form-pick-face' ).trigger( 'submit' );
                }
            },

            error: function(data) {
                console.log( "Texting Error!" );
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

     /*
      * Handle the click of btn-receive-upc button. Also used on texting .ajax success function.
      * see: http://stackoverflow.com/questions/18656718/memory-overhead-of-anonymous-functions-vs-named-functions-when-used-as-jquery-ca
      */
    function handle_ReceiveUPC_onClick() {
        console.log( "form-texting, #btn-receive-upc input onClicked" );
        if ($(this).is('[class~="btn-default"]')) {
            $(this).toggleClass('btn-default btn-success');
            $('#upcGridLines button .glyphicon-expand').trigger( jQuery.Event( "click" ) );
            $('#pickFaceLines button .glyphicon-collapse-down').trigger( jQuery.Event( "click" ) );
        }
        $('#form-texting #btn-close-tote[class~="btn-success"]').toggleClass('btn-default btn-success');
        return true;
    }

    function handle_CloseTote_onClick() {
        console.log( "form-texting, #btn-close-tote input onClicked" );
        if ($(this).is('[class~="btn-default"]')) {
            $(this).toggleClass('btn-default btn-success');
            $('#upcGridLines button .glyphicon-collapse-down').trigger( jQuery.Event( "click" ) );
            $('#pickFaceLines button .glyphicon-expand').trigger( jQuery.Event( "click" ) );
        }
        $('#form-texting #btn-receive-upc[class~="btn-success"]').toggleClass('btn-default btn-success');
        return true;
    }

    <!-- enter button click show clicked on console log -->
    $(document).ready(function() {
        $('#form-texting #text_entry').click(function() {
            console.log( "form-texting, #text_entry text clicked" );
        });
    });

    <!-- Receive_UPC button click should expand upcGridLines and collapse pickFaceLines -->
    $(document).ready(function() {
        $('#form-texting #btn-receive-upc').click(function() {
            console.log( "form-texting, #btn-receive-upc submit clicked" );
            handle_ReceiveUPC_onClick.call(this);
        });
    });

    <!-- Close_Tote button click should collapse upcGridLines and expand pickFaceLines -->
    $(document).ready(function() {
        $('#form-texting #btn-close-tote').click(function() {
            console.log( "form-texting, #btn-close-tote submit clicked" );
            handle_CloseTote_onClick.call(this);
        });
    });

</script>

@if(isset($userConversations))

    {!! Form::open(array('id' => 'form-texting')) !!}

    @foreach($userConversations as $index => $userConversation)
        <p id="uc-{{ $index }}" class="{{ $userConversation->klass }}"><span style="color:{{ $userConversation->color }}">{{ (strlen($userConversation->Text) > 0 ? $userConversation->Text : '&nbsp;') }}</span></p>
    @endforeach

    <p></p>
    {!! Form::text('text_entry', '', ['id' => 'text_entry', 'class' => 'form-control', (isset($quickReceive->Rework) && strlen($quickReceive->Rework) > 0 ? 'enabled' : 'disabled')]) !!}

    <p></p>
    {!! Form::submit( '', ['id' => 'btn-entry', 'name' => 'btn-entry', 'class' => 'btn']) !!}
    {!! Form::submit( \Lang::get('labels.buttons.Receive_UPC'), ['id' => 'btn-receive-upc', 'name' => 'btn-receive-upc', 'class' => 'btn '.(isset($receiveUPC) ? 'btn-primary' : 'btn-default')]) !!}
    {!! Form::submit( \Lang::get('labels.buttons.Close_Tote') , ['id' => 'btn-close-tote' , 'name' => 'btn-close-tote' , 'class' => 'btn '.(isset($closeTote) && $closeTote == 'refresh' ? 'btn-primary' : 'btn-default')]) !!}
    <p></p>

    {!! Form::close() !!}

@else
    <br><br>Texting goes here ...<br><br>
@endif

<!-- End of pages/quickReceive/texting.blade.php  -->
