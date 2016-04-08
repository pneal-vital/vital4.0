<?php namespace App\Jobs;

use App\Reports\ReworkReportInterface;
use Carbon\Carbon;
use vital3\Repositories\CountersRepositoryInterface;
use vital40\Repositories\ReceiptHistoryRepositoryInterface;
use \Excel;
use \Lang;
use \Log;
use Mail;

/**
 * See: http://laravel.com/docs/5.1/queues
 */
class ReworkReportJob extends Job {

    // already present in App\Job\Job.php we extend
    // implements SelfHandling, ShouldQueue
    //use InteractsWithQueue, SerializesModels;

    protected $fromDate = 0;
    protected $toDate = 0;
    protected $forUser = 'unknown';
    protected $exportType = 'csv';
    protected $emailTo = '';
    protected $jobID = false;
    protected $fileName;
    protected $results = '';

    protected $countersRepository;
    protected $receiptHistoryRepository;
    protected $reworkReport;

    /**
     * Create a new job instance.
     */
    public function __construct($fromDate, $toDate, $forUser, $exportType = 'csv', $emailTo = '', $jobID = false) {
        Log::debug("$forUser -__construct($fromDate, $toDate, $forUser, $exportType, $emailTo, $jobID)");
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->forUser = $forUser;
        $this->exportType = $exportType;
        $this->emailTo = $emailTo;
        $this->jobID = $jobID;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(
          CountersRepositoryInterface $countersRepository
        , ReceiptHistoryRepositoryInterface $receiptHistoryRepository
        , ReworkReportInterface $reworkReport
        , JobExperienceInterface $jobExperience
        , JobStatusInterface $jobStatus
    ) {
        $this->countersRepository = $countersRepository;
        $this->receiptHistoryRepository = $receiptHistoryRepository;
        $this->reworkReport = $reworkReport;

        $jobExperience->setClass($this);
        $jobStatus->starting($this, $this->jobID);

        // First count the number of UPCs that have Inventory
        // filter to count received
        $filter = [
            'fromDate' => $this->fromDate,
            'toDate'   => $this->toDate,
            'Activity' => preg_replace('/ - .*/', '', Lang::get('internal.receiptHistory.putUPCinTote')),
        ];
        $itemCount = $this->receiptHistoryRepository->countOn($filter);
        Log::debug($this->forUser.' - Receipt History count: '.$itemCount);
        $jobExperience->setNumberOfRecordsProcessed($itemCount);

        Log::debug($this->forUser." - calling export($this->fromDate, $this->toDate, $this->exportType)");
        $this->export();

        if(isset($this->emailTo) && strlen($this->emailTo) > 4) {
            Log::debug($this->forUser." - email results($this->emailTo)".(is_string($this->results) ? $this->results : serialize($this->results)));
            $this->email();
        }

        if(isset($this->results) == false) $this->results = $this->fileName;
        $rc = 0;
        if(is_string($this->results) && substr($this->results,0,5) == 'ERROR') $rc = 10;
        $jobStatus->completed($rc, $this->results);
        $jobExperience->ended();
    }

    /**
     * Handle a job failing.
     *
     * @return void
     */
    public function failed() {
        Log::debug($this->forUser.' - failed!');
        $this->results = "failed!";
    }


    /**
     * Export a Filtered Listing of the resource.
     *
     * See: ViewCreators/ExportTypeCreator for a list of the exportTypes we need to support.
     */
    public function export() {

        $reworkReport = ['fromDate' => $this->fromDate, 'toDate' => $this->toDate, 'forUser' => $this->forUser];
        //dd(__METHOD__."(".__LINE__.")",compact('reworkReport'));

        if($this->exportType == 'xls') {
            $reworkReports = $this->reworkReport->generate($this->fromDate, $this->toDate, 0);
            //dd(__METHOD__."(".__LINE__.")",compact('reworkReport','reworkReports'));
            Log::debug($this->forUser.' - xls report lines: '.count($reworkReports));

            // TODO file name should be calculated in a separate class
            $count = sprintf('%04d', $this->countersRepository->increment('exportFile'));
            $currentDate = Carbon::now()->format('YmdHis');
            $this->fileName = 'ReworkReports-'.$currentDate.$count;
            Log::debug($this->forUser.' - fileName: '.$this->fileName);

            // create Excel workbook
            $this->results =
                Excel::create($this->fileName, function ($excel) use ($reworkReport, $reworkReports) {

                    $excel->sheet('New sheet', function ($sheet) use ($reworkReport, $reworkReports) {

                        $sheet->loadView('pages.reworkReport.excel')
                            ->with('reworkReport',$reworkReport)
                            ->with('reworkReports',$reworkReports);
                    });

                })->store('xls',false,true);
        }

        elseif($this->exportType == 'csv') {
            $reworkReports = $this->reworkReport->generate($this->fromDate, $this->toDate, 0);
            //dd(__METHOD__."(".__LINE__.")",compact('reworkReport','reworkReports'));
            Log::debug($this->forUser.' - csv report lines: '.count($reworkReports));

            // TODO file name should be calculated in a separate class
            $count = sprintf('%04d', $this->countersRepository->increment('exportFile'));
            $currentDate = Carbon::now()->format('YmdHis');
            $this->fileName = 'ReworkReports-'.$currentDate.$count;
            Log::debug($this->forUser.' - fileName: '.$this->fileName);

            // create Excel workbook
            $this->results =
                Excel::create($this->fileName, function ($excel) use ($reworkReport, $reworkReports) {

                    $excel->sheet('New sheet', function ($sheet) use ($reworkReport, $reworkReports) {

                        $sheet->loadView('pages.reworkReport.excel')
                            ->with('reworkReport',$reworkReport)
                            ->with('reworkReports',$reworkReports);
                    });

                })->store('csv',false,true);
        }

        else {
            $this->results = Lang::get('internal.errors.export.unsupportedType', ['exportType' => $this->exportType]);
        }
        Log::debug($this->forUser.' - export completed');
    }

    /**
     * email results full file
     */
    public function email() {
        if(config('mail.driver') != 'sendmail') {
            Log::debug($this->forUser.' - config(mail.driver) error: '.config('mail.driver'));
            config(['mail.driver' => 'sendmail']);
            putenv('MAIL_DRIVER=sendmail');
            Log::debug($this->forUser.' - HACK, mail.driver changed: '.config('mail.driver'));
        }

        $emailData = [
            'email' => $this->emailTo,
            'fromDate' => $this->fromDate,
            'toDate' => $this->toDate,
            'forUser' => $this->forUser,
            'exportType' => $this->exportType,
            'status' => is_string($this->results) ? substr($this->results,0,6) : 'Completed',
            'attachment' => is_array($this->results) && isset($this->results['full']) ? $this->results['full'] : '',
        ];
        Log::info('send email for jobID ['.get_class($this).', '.$this->jobID.']', $emailData);
        Mail::send('emails.reworkReport', $emailData, function ($m) use ($emailData) {
            $m->from('VITaL4.0@legacyscs.com', 'VITaL4.0');
            $m->to($emailData['email'], $emailData['forUser'])->subject('Rework Report job');
            if(strlen($emailData['attachment']) > 3)
                $m->attach($emailData['attachment']);
        });

        Log::debug($this->forUser.' - email sent');
    }

}
