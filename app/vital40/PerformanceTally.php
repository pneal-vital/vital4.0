<?php namespace App\vital40;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Eloquent;
use \Auth;
use \Log;

class PerformanceTally extends Eloquent {

	/** Database connection to use */
	protected $connection = 'vitaldev';
	/** Table to use */
	protected $table = 'Performance_Tally';
	/** primaryKey column */
	protected $primaryKey = 'recordID';
	/** Allow DB to increment $primaryKey */
	public $incrementing = true;
	/** Does the table have laravel style timestamp fields */
	public $timestamps = false;

	/**
	 * Table Structure
	 * desc Performance_Tally;
    +----------------+---------------------+------+-----+---------------------+----------------+
    | Field          | Type                | Null | Key | Default             | Extra          |
    +----------------+---------------------+------+-----+---------------------+----------------+
    | recordID       | bigint(20) unsigned | NO   | PRI | NULL                | auto_increment |
    | dateStamp      | timestamp           | NO   |     | 0000-00-00 00:00:00 |                |
    | userName       | varchar(45)         | NO   |     | NULL                |                |
    | receivedUnits  | int(11)             | NO   |     | NULL                |                | <== populated by ArticleFlow.putUPCsIntoTote(..)
    | putAwayRec     | int(11)             | NO   |     | NULL                |                | <== populated by gunApp3.putToteIntoLocation(tote,loc)
    | putAwayRplComb | int(11)             | NO   |     | NULL                |                | <== populated by gunApp3.scanUPCsIntoTote(tote,loc)
    | putAwayRplSngl | int(11)             | NO   |     | NULL                |                | <== populated by gunApp3.putToteIntoLocation(tote,loc)
    | putAwayReserve | int(11)             | NO   |     | NULL                |                | <== populated by gunApp3.putToteIntoLocation(tote,loc)
    | replenTotes    | int(11)             | NO   |     | NULL                |                | <== populated by gunApp3.takeReplenJob()
    +----------------+---------------------+------+-----+---------------------+----------------+
	 */

	/**
	 * Attributes that are mass-assignable during inserts
	 * @var array
	 */
	protected $fillable = array('receivedUnits', 'putAwayRec', 'putAwayRplComb', 'putAwayRplSngl', 'putAwayReserve', 'replenTotes');

	/**
	 * Attributes not mass-assignable during inserts
	 * @var array
	 */
	protected $guarded = array('recordID', 'dateStamp', 'userName');


    /**
     * This function can set default values, and validate the entered field values.
     *
     * Register this function in an Event Listener, see: http://laravel.com/docs/master/events
     * or call it from EventServiceProvider::boot(..)
     */
    public function isCreating() {

        // set default values
        $this->receivedUnits = 0;
        $this->putAwayRec = 0;
        $this->putAwayRplComb = 0;
        $this->putAwayRplSngl = 0;
        $this->putAwayReserve = 0;
        $this->replenTotes = 0;

        $this->dateStamp = Carbon::now()->minute(00)->second(00);
        $this->userName = Auth::user()->name;

        /* validate the entered field values.
        if ( ! $this->isValid()) return false;
        */
    }

}
