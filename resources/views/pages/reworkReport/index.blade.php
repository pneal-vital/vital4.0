@extends('app')
<!-- extends('app') of pages/reworkReport/index.blade.php  -->

@section('content')
<!-- section('content') of pages/reworkReport/index.blade.php  -->

    <h1>
        @lang('labels.titles.Rework_Report')
    </h1>

    <hr>

    {{-- ReworkReport form of pages/reworkReport/index.blade.php --}}
    {!! Form::model($reworkReport, ['class' => 'form-horizontal', 'method' => 'patch', 'action' => 'ReworkReportController@filter']) !!}

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">

                    <div class="panel-heading clearfix">
                        @lang('labels.titles.Rework_Report_Filter')
                    </div>
                    <div class="panel-body">

                        @include('errors.list')

                        @include('pages.reworkReport.filter', ['labelType' => 'filter', 'submitButtonName' => 'Rework_Report_Filter'])

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filtered list of pages/reworkReport/index.blade.php --}}
    @include('pages.reworkReport.list')

    @if(count($reworkReports))
        {{-- Export form
        {!! Form::model($reworkReport, ['class' => 'form-horizontal', 'method' => 'patch', 'action' => 'ReworkReportController@export']) !!}
         --}}

        @include('fields.ddList', ['fieldName' => 'ExportType', 'lists' => $exportTypes, 'fieldSize' => 'col-md-6'])

        {{--
        @include('fields.button', ['submitButtonName' => 'Rework_Report_Export'])
         --}}
        <div class="form-group ">
            <div class="col-md-6 col-md-offset-4">
                <button type="button" class="btn btn-primary form-control"
                        title="Click to export a Rework Report" id="btn-Rework_Report_Export" name="Rework_Report_Export"
                        data-toggle="modal" data-target=".bs-example-modal-lg"
                >@lang('labels.buttons.Rework_Report_Export')</button>
            </div>
        </div>
        {{--
        {!! Form::close() !!}
         --}}
    @endif

    {!! Form::close() !!}

<!-- stop of pages/reworkReport/index.blade.php, section('content') -->
@stop


@section('footer')
<!-- section('footer') of pages/reworkReport/index.blade.php  -->

    <!-- Form::getValueAttribute($fieldName)  {{ null !== Form::getValueAttribute('ExportType') ? Form::getValueAttribute('ExportType') : 'not set' }} -->
    <!-- reworkReport->ExportType {{ isset($reworkReport['ExportType']) ? $reworkReport['ExportType'] : 'not set' }} -->
    <!-- reworkReport->fromDate {{ isset($reworkReport['fromDate']) ? $reworkReport['fromDate'] : 'not set' }} -->
    <!-- reworkReport->toDate {{ isset($reworkReport['toDate']) ? $reworkReport['toDate'] : 'not set' }} -->
    <!-- reworkReport->email {{ isset($reworkReport['email']) ? $reworkReport['email'] : 'not set' }} -->
    <!-- reworkReport->expectedCompletion {{ isset($reworkReport['expectedCompletion']) ? $reworkReport['expectedCompletion'] : $reworkReport['expectedCompletion'] = 'never..' }} -->

    {{--
    @if(isset($reworkReport['email']))
     --}}
    <!-- Modal -->
    <div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">

            <!-- Modal Content -->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">
                        @if(isset($reworkReport['status']))
                            @lang('labels.titles.Rework_Report_'.ucfirst($reworkReport['status']))
                        @else
                            @lang('labels.titles.Rework_Report')
                        @endif
                    </h4>
                </div>
                <div class="modal-body">
                    @if(isset($reworkReport['fromDate']) and isset($reworkReport['toDate']))
                        <p>You have selected a date range of {{ $reworkReport['fromDate'] }} to {{ $reworkReport['toDate'] }}
                    @endif
                    @if(isset($reworkReport['ExportType']))
                        with an export type of {{ $reworkReport['ExportType'] }}
                    @endif
                    .
                    <p>Your request has already submitted.</p>
                    @if(isset($reworkReport['elapsedTime']))
                        <p>This is expected to run for {{ $reworkReport['elapsedTime'] }} minutes.</p>
                    @endif
                    @if(isset($reworkReport['expectedCompletion']) and isset($reworkReport['status']) and $reworkReport['status'] == 'started')
                        <p>It is expect to complete at about $reworkReport['expectedCompletion'].</p>
                    @endif
                    <p>Would you like to wait, or receive the results in an email?</p>
                    <p>You may change the To: email address &nbsp; To: {{ isset($reworkReport['email']) ? $reworkReport['email'] : '_______' }}</p>
                    <p><button type='button' id="btn-Wait" name="btn_Wait" class="btn btn-primary" value="Wait" data-dismiss="modal">@lang('labels.buttons.Wait')</button>
                        &nbsp; or &nbsp;
                        <button type='button' id="btn-Email" name="btn_Email" class="btn btn-primary" value="Receive Email" data-dismiss="modal">@lang('labels.buttons.Receive_Email')</button></p>
                    {{--
                    {!! Form::model($reworkReport, ['route' => ['reworkReport.confirm', $reworkReport['expectedCompletion']], 'method' => 'post', 'class' => 'form-horizontal']) !!}

                    <div class="modal-footer">
                        <!-- div class="col-sm-8 col-md-offset-1" -->
                        <div class="col-sm-5 col-md-offset-1">
                            <a href="{!! route('reworkReport.review', [$reworkReport['expectedCompletion']]) !!}">
                                {!! Html::image('img/Thumbs_Up.jpg', "Thumbs Up",array('height'=>'100','width'=>'120')) !!}
                            </a>
                            <button id="btn-No" name="btn_No" class="btn btn-primary" value="No">No I'd better not. (Press this button!)</button>
                        </div>

                        <!-- div class="col-sm-5 col-md-offset-2" -->
                        <div class="col-sm-5 col-md-offset-1">
                            <a href="{!! route('reworkReport.confirm', [$reworkReport['expectedCompletion']]) !!}">
                                {!! Html::image('img/Ohoh.jpg', "Ohoh",array('height'=>'100','width'=>'120','name'=>'img_Confirm')) !!}
                            </a>
                            <button id="btn-Confirm" name="btn_Confirm" class="btn btn-primary" value="Confirm">Confirming 100% confident<br>(Don't touch this button!)</button>
                        </div>
                    </div>

                    {!! Form::close() !!}
                     --}}
                </div>
            </div>

        </div>
    </div>
    {{--
    @endif
     --}}

<!-- stop of pages/reworkReport/index.blade.php, section('footer') -->
@stop
