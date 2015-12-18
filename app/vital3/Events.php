<?php namespace App\vital3;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Events extends Eloquent {

	/** Database connection to use */
	protected $connection = 'vitaldev';
	/** Table to use */
	protected $table = 'Events';
	/** primaryKey column */
	protected $primaryKey = ['eventID', 'step'];
	/** Allow DB to increment $primaryKey */
	public $incrementing = false;
	/** Does the table have laravel style timestamp fields */
	public $timestamps = false;

	/**
	 * Table Structure
	 * desc Events;
    +----------------+-------------+------+-----+---------+-------+
    | Field          | Type        | Null | Key | Default | Extra |
    +----------------+-------------+------+-----+---------+-------+
    | eventID        | bigint(20)  | NO   | MUL | NULL    |       |
    | step           | int(11)     | NO   |     | NULL    |       |
    | plugIn         | varchar(85) | NO   |     | NULL    |       |
    | baseParameters | text        | YES  |     | NULL    |       |
    +----------------+-------------+------+-----+---------+-------+
    4 rows in set (0.02 sec)
     */

    /**
     * Field Mutators, used to validate, manipulate the value on get and set
     */
    public function getBaseParametersAttribute()
    {
        $un_serialized = [];
        if(isset($this->attributes['baseParameters']) && strlen($this->attributes['baseParameters']) > 6) {
            $un_serialized = unserialize($this->attributes['baseParameters']);
        }
        return $un_serialized;
    }

    public function setBaseParametersAttribute($value)
    {
        // Consider using Carbon::parse($value);
        $serialized = 'a:0:{}';
        if(isset($value) && is_array($value)) {
            $serialized = serialize($value);
        }
        $this->attributes['baseParameters'] = $serialized;
    }

}
