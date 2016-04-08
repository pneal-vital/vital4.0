<?php namespace App\vital40;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\DB;
use \Log;

class JobExperience extends Eloquent {

	/** Table to use */
	protected $table = 'job_experience';
	/** primaryKey column */
	protected $primaryKey = ['name', 'id'];
	/** Allow DB to increment $primaryKey */
	public $incrementing = false;
	/** Does the table have laravel style timestamp fields */
	public $timestamps = true;

	/**
	 * Table Structure
	 * desc job_experience;
    +------------+------------------+------+-----+---------------------+-------+
    | Field      | Type             | Null | Key | Default             | Extra |
    +------------+------------------+------+-----+---------------------+-------+
    | name       | varchar(255)     | NO   | PRI | NULL                |       |
    | id         | int(10) unsigned | NO   | PRI | NULL                |       |
    | experience | int(10) unsigned | NO   |     | NULL                |       |
    | elapsed    | int(10) unsigned | NO   |     | NULL                |       |
    | started    | timestamp        | NO   |     | 0000-00-00 00:00:00 |       |
    | created_at | timestamp        | NO   |     | 0000-00-00 00:00:00 |       |
    | updated_at | timestamp        | NO   |     | 0000-00-00 00:00:00 |       |
    +------------+------------------+------+-----+---------------------+-------+
    7 rows in set (0.01 sec)
     */

	/**
	 * Attributes that are mass-assignable during inserts
	 * @var array
	 */
	protected $fillable = array('name', 'experience', 'elapsed', 'started');

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
	protected function setKeysForSaveQuery(\Illuminate\Database\Eloquent\Builder $query)
	{
		$query->where($this->primaryKey[0], '=', $this->getIndexedKeyForSaveQuery(0));
		$query->where($this->primaryKey[1], '=', $this->getIndexedKeyForSaveQuery(1));

		return $query;
	}

	/**
	 * Get the primary key[Indexed] value for a save query.
	 *
	 * @return mixed
	 */
	protected function getIndexedKeyForSaveQuery($index)
	{
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
    public function isCreating()
    {
        // set default values
        $this->id = JobExperience::select(DB::raw('max(id) as maxid'))->where('name', $this->name)->first()->maxid + 1;
        Log::debug('Key: ['.$this->name.','.$this->id.']');
        if($this->id > 10)
            JobExperience::where('name', $this->name)->where('id', '<', $this->id - 9)->delete();
    }

}
