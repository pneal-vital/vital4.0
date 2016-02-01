<?php namespace vital40;

use Illuminate\Database\Eloquent\Model as Eloquent;

class SessionType extends Eloquent {

	/** Database connection to use */
	//protected $connection = 'laravel'; <== laravel is the default
	/** Table to use */
	protected $table = 'sessions';
	/** primaryKey is objectID */
	protected $primaryKey = 'id';
	/** Allow DB to increment PK */
	public $incrementing = false;
	/** Does the table have laravel style timestamp fields */
	public $timestamps = false;

	/**
	 * Table Structure
	 * desc sessions;
	+---------------+--------------+------+-----+---------+-------+
	| Field         | Type         | Null | Key | Default | Extra |
	+---------------+--------------+------+-----+---------+-------+
	| id            | varchar(255) | NO   | PRI | NULL    |       |
	| payload       | text         | NO   |     | NULL    |       |
	| last_activity | int(11)      | NO   |     | NULL    |       |
	+---------------+--------------+------+-----+---------+-------+
	3 rows in set (0.00 sec)
	 */

	/**
	 * Attributes that are mass-assignable during inserts
	 * @var array
	 */
	protected $fillable = array('id', 'payload', 'last_activity');

}
