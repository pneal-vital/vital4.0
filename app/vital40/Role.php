<?php namespace vital40;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Role extends Eloquent {

	/** Database connection to use */
	//protected $connection = 'laravel'; <== laravel is the default
	/** Table to use */
	protected $table = 'roles';
	/** primaryKey is objectID */
	protected $primaryKey = 'id';
	/** Allow DB to increment PK */
	public $incrementing = true;

	/**
	 * Table Structure
	 * desc roles;
    +--------------+------------------+------+-----+---------------------+----------------+
    | Field        | Type             | Null | Key | Default             | Extra          |
    +--------------+------------------+------+-----+---------------------+----------------+
    | id           | int(10) unsigned | NO   | PRI | NULL                | auto_increment |
    | name         | varchar(255)     | NO   | UNI | NULL                |                |
    | display_name | varchar(255)     | YES  |     | NULL                |                |
    | description  | varchar(255)     | YES  |     | NULL                |                |
    | created_at   | timestamp        | NO   |     | 0000-00-00 00:00:00 |                |
    | updated_at   | timestamp        | NO   |     | 0000-00-00 00:00:00 |                |
    +--------------+------------------+------+-----+---------------------+----------------+
    6 rows in set (0.00 sec)
	 */

	/**
	 * Attributes that are mass-assignable during inserts
	 * @var array
	 */
	protected $fillable = array('name', 'display_name', 'description');

	/**
	 * Attributes not mass-assignable during inserts
	 * @var array
	 */
	protected $guarded = array('id', 'created_at', 'updated_at');

}
