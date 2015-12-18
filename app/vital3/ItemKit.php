<?php namespace App\vital3;

use Illuminate\Database\Eloquent\Model as Eloquent;

class ItemKit extends Eloquent {

	/** Database connection to use */
	protected $connection = 'vitaldev';
	/** Table to use */
	protected $table = 'itemKit';
	/** primaryKey column */
	protected $primaryKey = 'containerID';
	/** Allow DB to increment $primaryKey */
	public $incrementing = false;
	/** Does the table have laravel style timestamp fields */
	public $timestamps = false;

	/**
	 * Table Structure
	 * desc itemKit;
	+-------------+------------+------+-----+---------+----------------+
	| Field       | Type       | Null | Key | Default | Extra          |
	+-------------+------------+------+-----+---------+----------------+
	| containerID | bigint(20) | NO   | PRI | NULL    | auto_increment |
	| parentID    | bigint(20) | YES  | MUL | 0       |                | references Article Item
	| objectID    | bigint(20) | YES  | MUL | 0       |                | references UPC Item
	| Quantity    | bigint(20) | YES  | MUL | 0       |                |
	+-------------+------------+------+-----+---------+----------------+
	4 rows in set (0.05 sec)
	 */

	/**
	 * Attributes that are mass-assignable during inserts
	 * @var array
	 */
	protected $fillable = array('parentID', 'objectID', 'Quantity');

	/**
	 * Attributes not mass-assignable during inserts
	 * @var array
	 */
	protected $guarded = array('containerID');

}
