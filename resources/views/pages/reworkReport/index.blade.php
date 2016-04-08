@extends('pages.panelList')

@section('title')
    <!-- section('title') of pages/reworkReport/index.blade.php  -->

    @lang('labels.titles.Rework_Report')

    <!-- stop of pages/reworkReport/index.blade.php, section('title') -->
@stop

@section('heading')
    <!-- section('title') of pages/reworkReport/index.blade.php  -->

    @lang('labels.titles.Rework_Report_Filter')

    <!-- stop of pages/reworkReport/index.blade.php, section('heading') -->
@stop

@section('form')
    <!-- section('form') of pages/reworkReport/index.blade.php  -->

    {{-- Filter fields --}}
    {!! Form::model($reworkReport, ['class' => 'form-horizontal', 'method' => 'patch', 'action' => 'ReworkReportController@filter']) !!}

        @include('pages.reworkReport.filter', ['labelType' => 'filter', 'submitButtonName' => 'Rework_Report_Filter'])

    {!! Form::close() !!}

    <!-- stop of pages/reworkReport/index.blade.php, section('form') -->
@stop

@section('list')
    <!-- section('list') of pages/reworkReport/index.blade.php  -->

    {{-- Filtered list --}}
    @include('pages.reworkReport.list')

    @if(count($reworkReports))
        {!! Form::model($reworkReport, ['class' => 'form-horizontal', 'method' => 'patch', 'action' => 'ReworkReportController@export']) !!}

        @include('fields.ddList', ['fieldName' => 'ExportType', 'lists' => $exportTypes, 'fieldSize' => 'col-md-6'])

        @include('fields.button', ['submitButtonName' => 'Rework_Report_Export'])

        {!! Form::close() !!}
    @endif

    <!-- stop of pages/reworkReport/index.blade.php, section('list') -->
@stop


@section('footer')
<!-- section('footer') of pages/reworkReport/index.blade.php  -->

    <!-- reworkReport->email {{ isset($reworkReport['email']) ? $reworkReport['email'] : 'not set' }} -->
    <!-- reworkReport->elapsedTime {{ isset($reworkReport['elapsedTime']) ? $reworkReport['elapsedTime'] : 'not set' }} -->
    <!-- reworkReport->expectedCompletion {{ isset($reworkReport['expectedCompletion']) ? $reworkReport['expectedCompletion'] : 'not set' }} -->

    <!-- Modal class="modal fade bs-example-modal-lg" -->
    <div class="modal bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">

            <!-- Modal Content -->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">
                        @lang('labels.titles.Rework_Report')
                    </h4>
                </div>
                <div class="modal-body">
                    <p id="paragraph_1">
                    Rework Report selection date range of {{ $reworkReport['fromDate'] }} to {{ $reworkReport['toDate'] }}.
                    @if(isset($reworkReport['elapsedTime']))
                        This report is expected to run for {{ $reworkReport['elapsedTime'] }} minutes.
                    @endif
                    @if(isset($reworkReport['expectedCompletion']))
                        If submitted now, it is not expected to finish before {{ $reworkReport['expectedCompletion'] }}.
                    @endif
                    </p>
                    <p>Would you like to wait, or receive the results in an email?</p>
                    {!! Form::model($reworkReport, ['id' => 'sendEmail', 'route' => ['reworkReport.email'], 'method' => 'post', 'class' => 'form-horizontal']) !!}

                    <input type="hidden" name="ExportType" value=""/>
                    <input type="hidden" name="fromDate" value=""/>
                    <input type="hidden" name="toDate" value=""/>
                    <input type="hidden" name="elapsedTime" value="{{ isset($reworkReport['elapsedTime']) ? $reworkReport['elapsedTime'] : 0 }}"/>

                    <div class="modal-footer">
                        @include('fields.textEntry', ['fieldName' => 'email', 'labelSize' => 'col-md-2 col-md-offset-4', 'fieldSize' => 'col-md-6'])
                        <div class="col-md-4 text-left"> <!-- data-dismiss="modal" -->
                            <button type='submit' id="btn-Wait" name="btn_Wait" class="btn btn-primary" value="Wait"
                            >@lang('labels.buttons.Wait')</button>
                        </div>
                        <div class="col-md-8">
                            <button type='submit' id="btn-Email" name="btn_Email" class="btn btn-primary" value="Receive Email"
                            >@lang('labels.buttons.Receive_Email')</button>
                        </div>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>

        </div>
    </div>

    <script>

        $( document ).ready(function() {
            console.log( "document loaded" );
        });

        $( window ).load(function() {
            console.log( "window loaded" );
        });

        <!-- initializes and invokes show immediately
        function loadFn( jQuery ) {
            var exportType = $('select[name="ExportType"] > option').attr("value");
            console.log( "ExportType -> value: " + exportType );
            $('#myModal').modal('show');
            console.log( "#myModal -> show" );
        }
         -->

        <!-- $( window ).load( loadFn ); -->

        $( '#btn-Wait' ).click(function() {
            $('#myModal').modal('hide');
            console.log( "#myModal -> hide" );
        });


        function exportTypeSelected( jQuery ) {
            <!-- capture exportType, fromDate & toDate -->
            var exportType = $(this).find(':selected').val();
            console.log( "ExportType -> selected: " + exportType );
            var fromDate = $('input[id="fromDate"]').val();
            var toDate = $('input[id="toDate"]').val();
            var elapsedTime = $('#sendEmail > input[name="elapsedTime"]').attr('value');
            console.log( "fromDate, toDate: " + fromDate + ", " + toDate + ", " + elapsedTime );

            if(exportType != '0' && elapsedTime > 1) {
                <!-- set form hidden fields -->
                $('#sendEmail > input[name="ExportType"]').attr('value', exportType);
                $('#sendEmail > input[name="fromDate"]').attr('value', fromDate);
                $('#sendEmail > input[name="toDate"]').attr('value', toDate);

                <!-- update ", with an export type of " + exportType into the modal-body paragraph 1 text -->
                var paragraph_1 = $('div[class="modal-body"] > p[id="paragraph_1"]');
                var p1_Text = paragraph_1.text();
                var commaN = p1_Text.indexOf(",");
                var dotN = p1_Text.indexOf(".");
                var new_p1_Text = "";
                if(commaN == -1 || dotN < commaN) {
                    new_p1_Text = p1_Text.substr(0,dotN).concat(", with an export type of " + exportType, p1_Text.substr(dotN));
                } else {
                    var exportTypeN = p1_Text.indexOf(", with an export type of ") + 25;
                    if(exportTypeN > 25) {
                        new_p1_Text = p1_Text.substr(0,exportTypeN).concat(exportType, p1_Text.substr(exportTypeN + 3));
                    }
                }
                if(new_p1_Text.length > 10) {
                    console.log( "modal-body > mbParagraph -> new_p1_Text: " + new_p1_Text );
                    paragraph_1.text(new_p1_Text);
                }

                <!-- invoke modal show -->
                $('#myModal').modal('show');
                console.log( "#myModal -> shown" );
            }
        }

        $( 'select[name="ExportType"]' ).change( exportTypeSelected );

    </script>

<!-- stop of pages/reworkReport/index.blade.php, section('footer') -->
@stop
