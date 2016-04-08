<?php namespace App\vital40;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\DB;
use \Log;

class JobStatus extends Eloquent {

	/** Table to use */
	protected $table = 'job_status';
	/** primaryKey column */
	protected $primaryKey = ['name', 'id'];
	/** Allow DB to increment $primaryKey */
	public $incrementing = false;
	/** Does the table have laravel style timestamp fields */
	public $timestamps = true;

	/**
	 * Table Structure
	 * desc job_status;
	+------------+------------------+------+-----+---------------------+-------+
	| Field      | Type             | Null | Key | Default             | Extra |
	+------------+------------------+------+-----+---------------------+-------+
	| name       | varchar(255)     | NO   | PRI | NULL                |       |
	| id         | int(10) unsigned | NO   | PRI | NULL                |       |
	| parameters | varchar(255)     | NO   |     | NULL                |       |
	| attempts   | int(10) unsigned | YES  |     | NULL                |       |
	| requested  | timestamp        | NO   |     | 0000-00-00 00:00:00 |       |
	| started    | timestamp        | YES  |     | NULL                |       |
	| completed  | timestamp        | YES  |     | NULL                |       |
	| created_at | timestamp        | NO   |     | 0000-00-00 00:00:00 |       |
	| updated_at | timestamp        | NO   |     | 0000-00-00 00:00:00 |       |
	| rc         | int(10) unsigned | YES  |     | NULL                |       |
	| results    | text             | YES  |     | NULL                |       |
	+------------+------------------+------+-----+---------------------+-------+
	11 rows in set (0.00 sec)
     */

	/**
	 * Attributes that are mass-assignable during inserts
	 * @var array
	 */
	protected $fillable = array('name', 'parameters', 'attempts', 'requested', 'started', 'completed', 'rc', 'results');

	/**
	 * Attributes not mass-assignable during inserts
	 * @var array
	 */
	protected $guarded = array('id', 'created_at', 'updated_at');

	/**
	 * Set the keys for a save update query.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	protected function setKeysForSaveQuery(\Illuminate\Database\Eloquent\Builder $query) {
		$query->where($this->primaryKey[0], '=', $this->getIndexedKeyForSaveQuery(0));
		$query->where($this->primaryKey[1], '=', $this->getIndexedKeyForSaveQuery(1));

		return $query;
	}

	/**
	 * Get the primary key[Indexed] value for a save query.
	 *
	 * @return mixed
	 */
	protected function getIndexedKeyForSaveQuery($index) {
		if (isset($this->original[$this->primaryKey[$index]])) {
			return $this->original[$this->primaryKey[$index]];
		}

		return $this->getAttribute($this->primaryKey[$index]);
	}

    /**
     * This function can set default values, and validate the entered field values.
     *
     * Register this function in an Event Listener, see: http://laravel.com/docs/master/events
     * or call it from EventServiceProvider::boot(..)
     */
    public function isCreating() {
        // set default values
        $this->id = JobStatus::select(DB::raw('max(id) as maxid'))->where('name', $this->name)->first()->maxid + 1;
        Log::debug('Key: ['.$this->name.','.$this->id.']');
    }

    /**
     * Field Mutators, used to validate, manipulate the value on get and set
     */
    public function getParametersAttribute()
    {
        $un_serialized = [];
        if(isset($this->attributes['parameters']) && strlen($this->attributes['parameters']) > 6) {
            $un_serialized = unserialize($this->attributes['parameters']);
        }
        return $un_serialized;
    }

    public function setParametersAttribute($value)
    {
        // Consider using Carbon::parse($value);
        $serialized = 'a:0:{}';
        if(isset($value) && is_array($value)) {
            $serialized = serialize($value);
        }
        $this->attributes['parameters'] = $serialized;
    }

    public function getResultsAttribute()
    {
        $un_serialized = [];
        if(isset($this->attributes['results']) && strlen($this->attributes['results']) > 6) {
            $un_serialized = unserialize($this->attributes['results']);
        }
        return $un_serialized;
    }

    public function setResultsAttribute($value)
    {
        // Consider using Carbon::parse($value);
        $serialized = 'a:0:{}';
        if(isset($value) && is_array($value)) {
            $serialized = serialize($value);
        }
        $this->attributes['results'] = $serialized;
    }

	public function getJobIDAttribute() {
		$jobID = [];
		if(isset($this->attributes['name']) && strlen($this->attributes['name']) > 0) {
			$jobID['name'] = $this->attributes['name'];
		}
		if(isset($this->attributes['id']) && $this->attributes['id'] > 0) {
            $jobID['id'] = $this->attributes['id'];
		}
		return $jobID;
	}

	public function getStatusAttribute() {
		$status = "not found";
		if(isset($this->attributes['requested']) && strlen($this->attributes['requested']) > 10) {
			$status = "Requested";
		}
		if(isset($this->attributes['started']) && strlen($this->attributes['started']) > 10) {
			$status = "Started";
		}
		if(isset($this->attributes['completed']) && strlen($this->attributes['completed']) > 10) {
			$status = "Completed";
		}
		return $status;
	}

}
