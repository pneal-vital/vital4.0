<!-- Beginning of pages/quickReceive/pickFaceLines.blade.php  -->

<script>
    {{--
        A $( document ) .on 'submit' of the form with id="form-pick-face", run unnamed function
        see: http://api.jquery.com/on/#on-events-selector-data
    --}}
    $( document ).on('submit', 'form[id="form-pick-face"]', function(event) {
        console.log( "form-pick-face, on submit!" );
        event.preventDefault();

{{-- see: http://laravel.io/forum/02-06-2015-ajax-code-works-in-laravel-4-but-not-in-laravel-5 --}}
        var url = "{!! route('quickReceive.pickFaceLines') !!}";
        console.log( "form-pick-face, url: ", url );

{{-- see: http://api.jquery.com/jquery.ajax/ --}}
        $.ajax({
            url: url,
            type: 'POST',
            data: $(this).closest('form').serialize(),

            success: function(data) {
                // Success...
                console.log( "form-pick-face, Refresh Success!" );
                var upcIndex = "{!! route('upc.index') !!}";
                // empty tbody, to remove all lines we don't want displayed this round
                $( '#form-pick-face table tbody').empty();
                // Pick Face lines
                var previous_objectID = 0;
                var ind = 0;
                $.each(data.pickFaceLines, function(index, value) {
                    console.log('data.pickFaceLines.'+index, value);
                    if(previous_objectID != value.objectID) {
                        // when we can find an old line at the end, remove it
                        $( 'tr[id="pf-'+previous_objectID+'-'+ind+'"]' ).remove();
                        // setup for new objectID
                        previous_objectID = value.objectID;
                        ind = 0;
                    }
                    if(ind == 0) {
                        var tr = $('tr[id="pf-'+value.objectID+'-'+ind+'"]');
                        if(tr.length === 0) {
                            // when we can't find this line, insert as first
                            console.log( "form-pick-face, ( pf-"+value.objectID+'-'+ind+" ).prependTo(#form-pick-face table tbody)" );
                            @if(Entrust::hasRole(['support']))
                                $( '<tr id="pf-'+value.objectID+'-'+ind+'" >' +
                                    '<td id="pf-0"><span style="color:darkblue"></span></td>' +
                                    '<td id="pf-1" colspan="2"><span style="color:darkblue"></span></td>' +
                                    '<td id="pf-2" colspan="4"><span style="color:darkblue"></span></td>' +
                                    '</tr>' ).prependTo( '#form-pick-face table tbody' );
                            @else
                                $( '<tr id="pf-'+value.objectID+'-'+ind+'" >' +
                                    '<td id="pf-1" colspan="2"><span style="color:darkblue"></span></td>' +
                                    '<td id="pf-2" colspan="4"><span style="color:darkblue"></span></td>' +
                                    '</tr>' ).prependTo( '#form-pick-face table tbody' );
                            @endif
                            tr = $('tr[id="pf-'+value.objectID+'-'+ind+'"]');
                        }
                        @if(Entrust::hasRole(['support']))
                        tr.find('td[id="pf-0"] span').text(value.objectID);
                        @endif
                        tr.find('td[id="pf-1"] span').html(value.Client_SKU);
                        tr.find('td[id="pf-2"] span').text(value.Description);
                        ind++;
                    }
                    var tr = $('tr[id="pf-'+value.objectID+'-'+ind+'"]');
                    console.log( "form-pick-face, tr: "+tr.attr('id') );
                    if(tr.length === 0) {
                        // when we can't find this line, insertAfter the previous line
                        console.log( "form-pick-face, ( pf-"+value.objectID+'-'+ind+" ).insertAfter ( pf-"+value.objectID+'-'+(ind-1)+" )" );
                        @if(Entrust::hasRole(['support']))
                            $( '<tr id="pf-'+value.objectID+'-'+ind+'" >' +
                                '<td/><td id="pf-3"/><td id="pf-4"/><td id="pf-5"/><td id="pf-6"/><td id="pf-7"/><td id="pf-8"/>' +
                                '</tr>' ).insertAfter( 'tr[id="pf-'+value.objectID+'-'+(ind-1)+'"]' );
                        @else
                            $( '<tr id="pf-'+value.objectID+'-'+ind+'" >' +
                                '<td id="pf-3"/><td id="pf-4"/><td id="pf-5"/><td id="pf-6"/><td id="pf-7"/><td id="pf-8"/>' +
                                '</tr>' ).insertAfter( 'tr[id="pf-'+value.objectID+'-'+(ind-1)+'"]' );
                        @endif
                        tr = $('tr[id="pf-'+value.objectID+'-'+ind+'"]');
                    }
                    tr.find('td[id="pf-3"]').text(value.Quantity);
                    tr.find('td[id="pf-4"]').text(value.Status);
                    tr.find('td[id="pf-5"]').html(value.Carton_ID);
                    tr.find('td[id="pf-6"]').html(value.Pallet_ID);
                    tr.find('td[id="pf-7"]').html(value.Location_Name);
                    tr.find('td[id="pf-8"]').html(value.LocType);
                    //$('tr[id="pf-'+value.objectID+'-'+ind+'"] > td[id="pf-8"]').html(value.LocType);
                    ind++;
                });
                // when we can find an old line at the end, remove it
                $( 'tr[id="pf-'+previous_objectID+'-'+ind+'"]' ).remove();
            },

            error: function(data) {
                console.log( "form-pick-face, Refresh Error!" );
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

    <!-- enable pickFaceLines to be expandable -->
    $(document).ready(function() {
        $('#pickFaceLines button').click(function() {
            console.log( "form-pick-face, #pickFaceLines button clicked" );
            $(this).parents('#pickFaceLines').find('.ajax-refresh').slideToggle(1000);
            $(this).find('span').toggleClass('glyphicon-expand glyphicon-collapse-down');
            return false;
        });
    });

</script>

<!-- Pick Face lines -->
<div id="pickFaceLines">
    <div class="col-sm-9 row">
        <button type="button" class="btn btn-default btn-sm">
            <span class="glyphicon glyphicon-expand"></span> {!! Lang::get('labels.buttons.Pick_Face_lines') !!}
        </button>
    </div>
    <div class="row">
        <div class="col-sm-9 ajax-refresh collapse">

            {!! Form::open(array('id' => 'form-pick-face')) !!}

            {{--
            * UPC grid;
               0 => {
                  +"objectID": "6214474190"
                  +"Client_SKU": "63664321201"
                  +"Description": "MF4O115000 CLASSICPEA, XS, NAVY"
                  +"Quantity": "3"
                  +"Status": "OPEN"
                  +"Carton_ID": "52 0015 3598"
                  +"Pallet_ID": "AB1111"
                  +"Location_Name": "W-AB1111"
                  +"LocType": "PICK"
                }
            --}}

            <table class="table table-bordered">
                <thead>
                    <tr>
                        @if(Entrust::hasRole(['support']))
                            <th><span style="color:darkblue">{!! Lang::get('labels.objectID')      !!}</span></th>
                        @endif
                        <th colspan="2"><span style="color:darkblue">{!! Lang::get('labels.Client_SKU')  !!}</span></th>
                        <th colspan="4"><span style="color:darkblue">{!! Lang::get('labels.Description') !!}</span></th>
                    </tr>
                    <tr>
                        @if(Entrust::hasRole(['support']))
                            <th></th>
                        @endif
                        <th>{!! Lang::get('labels.Qty')           !!}</th>
                        <th>{!! Lang::get('labels.Status')        !!}</th>
                        <th>{!! Lang::get('labels.Carton_ID')     !!}</th>
                        <th>{!! Lang::get('labels.Pallet_ID')     !!}</th>
                        <th>{!! Lang::get('labels.Location_Name') !!}</th>
                        <th>{!! Lang::get('labels.LocType')       !!}</th>
                    </tr>
                </thead>
                <tbody>

                @if(isset($pickFaceLines) && count($pickFaceLines) > 0)

                    <!-- {{ $previous_objectID = 0 }} -->
                    @foreach($pickFaceLines as $index => $pickFaceLine)

                        <!-- {{ $ind = ($previous_objectID != $pickFaceLine->objectID ? 0 : $ind) }} -->
                        @if($ind == 0)
                            <tr id="pf-{{ $pickFaceLine->objectID }}-{{ $ind }}" >
                                @if(Entrust::hasRole(['support']))
                                    <td id="pf-0" ><span style="color:darkblue">{{ $pickFaceLine->objectID      }}</span></td>
                                @endif
                                <td id="pf-1" colspan="2" ><span style="color:darkblue">{{ $pickFaceLine->Client_SKU  }}</span></td>
                                <td id="pf-2" colspan="4" ><span style="color:darkblue">{{ $pickFaceLine->Description }}</span></td>
                            </tr>
                            <!-- {{ $previous_objectID = $pickFaceLine->objectID }} -->
                            <!-- {{ $ind += 1 }} -->
                        @endif
                        <tr id="pf-{{ $pickFaceLine->objectID }}-{{ $ind }}" >
                            @if(Entrust::hasRole(['support']))
                                <td ></td>
                            @endif
                            <td id="pf-3" >{{ $pickFaceLine->Quantity      }}</td>
                            <td id="pf-4" >{{ $pickFaceLine->Status        }}</td>
                            <td id="pf-5" >{{ $pickFaceLine->Carton_ID     }}</td>
                            <td id="pf-6" >{{ $pickFaceLine->Pallet_ID     }}</td>
                            <td id="pf-7" >{{ $pickFaceLine->Location_Name }}</td>
                            <td id="pf-8" >{{ $pickFaceLine->LocType       }}</td>
                        </tr>
                        <!-- {{ $ind += 1 }} -->

                    @endforeach

                @endif

                </tbody>
            </table>

            {!! Form::close() !!}

        </div>
    </div>
</div>

<!-- End of pages/quickReceive/pickFaceLines.blade.php -->
