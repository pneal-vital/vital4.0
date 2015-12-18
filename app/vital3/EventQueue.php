<?php namespace App\vital3;

use Illuminate\Database\Eloquent\Model as Eloquent;

class EventQueue extends Eloquent {

	/** Database connection to use */
	protected $connection = 'vitaldev';
	/** Table to use */
	protected $table = 'EventQueue';
	/** primaryKey column */
	protected $primaryKey = 'recordID';
	/** Allow DB to increment $primaryKey */
	public $incrementing = true;
	/** Does the table have laravel style timestamp fields */
	public $timestamps = false;

	/**
	 * Table Structure
	 * desc EventQueue;
    +------------+------------+------+-----+---------+----------------+
    | Field      | Type       | Null | Key | Default | Extra          |
    +------------+------------+------+-----+---------+----------------+
    | recordID   | bigint(20) | NO   | PRI | NULL    | auto_increment |
    | eventID    | bigint(20) | NO   |     | NULL    |                |
    | parameters | text       | NO   |     | NULL    |                |
    | priority   | int(11)    | NO   |     | 10      |                |
    +------------+------------+------+-----+---------+----------------+
    4 rows in set (0.04 sec)
     */

	/**
	 * Attributes that are mass-assignable during inserts
	 * @var array
	 */
	protected $fillable = array('eventID', 'parameters', 'priority');

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

}
