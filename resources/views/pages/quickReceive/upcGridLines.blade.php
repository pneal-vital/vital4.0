<!-- Beginning of pages/quickReceive/upcGridLines.blade.php  -->

@if(isset($upcGridLines) && count($upcGridLines) > 0)

<script>
    {{--
        A $( document ) .on 'submit' of the form with id="form-UPC-grid", run unnamed function
        see: http://api.jquery.com/on/#on-events-selector-data
    --}}
    $( document ).on('submit', 'form[id="form-UPC-grid"]', function(event) {
        console.log( "form-UPC-grid, on submit!" );
        event.preventDefault();

        {{-- see: http://laravel.io/forum/02-06-2015-ajax-code-works-in-laravel-4-but-not-in-laravel-5 --}}
        var url = "{!! route('quickReceive.upcGridLines') !!}";
        console.log( "form-UPC-grid, url: ", url );

        {{-- see: http://api.jquery.com/jquery.ajax/ --}}
        $.ajax({
            url: url,
            type: 'POST',
            data: $(this).closest('form').serialize(),

            success: function(data) {
                // Success...
                console.log( "form-UPC-grid, Refresh Success!" );
                var upcIndex = "{!! route('upc.index') !!}";
                // UPC grid lines
                $.each(data.upcGridLines, function(index, value) {
                    // console.log('data.upcGridLines.'+index, value);
                    $('tr[id="uga-'+index+'"] > td[id="uga-0"]').text(value.Description);
                    $('tr[id="ugb-'+index+'"] > td[id="ugb-0"]').html('<a href="'+upcIndex+'/'+value.upcID+'">'+value.Client_SKU+'</a>');
                    $('tr[id="ugb-'+index+'"] > td[id="ugb-1"]').text(value.Expected);
                    $('tr[id="ugb-'+index+'"] > td[id="ugb-2"]').text(value.Received);
                    $('tr[id="ugb-'+index+'"] > td[id="ugb-3"]').text(value.Variance);
                    $('tr[id="ugb-'+index+'"] > td[id="ugb-4"]').html(value.Totes);
                });
            },

            error: function(data) {
                console.log( "form-UPC-grid, Refresh Error!" );
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

    <!-- enable upcGridLines to be expandable -->
    $(document).ready(function(){
        $('#upcGridLines button').click(function() {
            console.log( "form-UPC-grid, #upcGridLines button clicked" );
            $(this).parents('#upcGridLines').find('.ajax-refresh').slideToggle(1000);
            $(this).find('span').toggleClass('glyphicon-expand glyphicon-collapse-down');
            return false;
        });
    });

</script>

<!-- UPCs grid lines -->
<div id="upcGridLines">
    <div class="col-sm-9 row">
        <button type="button" class="btn btn-default btn-sm">
            <span class="glyphicon glyphicon-collapse-down"></span> {!! Lang::get('labels.buttons.UPC_grid_lines') !!}
        </button>
    </div>
    <div class="row">
        <div class="col-sm-9 ajax-refresh">

            {!! Form::open(array('id' => 'form-UPC-grid')) !!}


            {{--
            * UPC grid;
            +----------------------+-------------+------+-----+---------+-------+
            | Field                | Type        | Null | Key | Default | Extra |
            +----------------------+-------------+------+-----+---------+-------+
            | upcID                | big int(20) | YES  |     | 0       |       |
            | Client_SKU           | varchar(85) | YES  |     | NULL    |       |
            | Description          | varchar(85) | YES  |     | NULL    |       |
            | Expected             | big int(20) | YES  |     | 0       |       |
            | Received             | big int(20) | YES  |     | 0       |       |
            | Variance             | varchar(85) | YES  |     | NULL    |       |
            | Totes                | varchar(85) | YES  |     | NULL    |       |
            +----------------------+-------------+------+-----+---------+-------+
            --}}

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>{!! Lang::get('labels.UPC')      !!}</th>
                        <th>{!! Lang::get('labels.Expected') !!}</th>
                        <th>{!! Lang::get('labels.Scanned')  !!}</th>
                        <th>{!! Lang::get('labels.Variance') !!}</th>
                        <th>{!! Lang::get('labels.Totes')    !!}</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($upcGridLines as $index => $upcGridLine)

                        <tr id="uga-{{ $index }}" >
                            <td id="uga-0" colspan="5">{{ $upcGridLine->Description }}</td>
                        </tr>
                        <tr id="ugb-{{ $index }}" >
                            <td id="ugb-0" >{!! link_to_route('upc.show' , $upcGridLine->Client_SKU, ['id' => $upcGridLine->upcID]) !!}</td>
                            <td id="ugb-1" >{{ $upcGridLine->Expected }}</td>
                            <td id="ugb-2" >{{ $upcGridLine->Received }}</td>
                            <td id="ugb-3" >{{ $upcGridLine->Variance }}</td>
                            <td id="ugb-4" >{{ $upcGridLine->Totes    }}</td>
                        </tr>

                    @endforeach

                </tbody>
            </table>

            {!! Form::close() !!}

        </div>
    </div>
</div>

@endif

<!-- End of pages/quickReceive/upcGridLines.blade.php -->
