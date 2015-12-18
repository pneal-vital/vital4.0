<?php namespace App\vital3;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Counters extends Eloquent {

	/** Database connection to use */
	protected $connection = 'vitaldev';
	/** Table to use */
	protected $table = 'Counters';
	/** primaryKey column */
	protected $primaryKey = 'Name';
	/** Allow DB to increment $primaryKey */
	public $incrementing = false;
	/** Does the table have laravel style timestamp fields */
	public $timestamps = false;

	/**
	 * Table Structure
	 * desc Counters;
    +-------+-------------+------+-----+---------+-------+
    | Field | Type        | Null | Key | Default | Extra |
    +-------+-------------+------+-----+---------+-------+
    | Name  | varchar(85) | NO   | PRI | NULL    |       |
    | Value | bigint(20)  | NO   |     | NULL    |       |
    +-------+-------------+------+-----+---------+-------+
    2 rows in set (0.05 sec)
	 */

    /**
     * Attributes that are mass-assignable during inserts
     * @var array
     */
    protected $fillable = array('Name');

	/**
	 * Attributes not mass-assignable during inserts
	 * @var array
	 */
	protected $guarded = array('Value');

}
