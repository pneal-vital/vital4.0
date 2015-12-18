<?php namespace vital40;

use Illuminate\Database\Eloquent\Model as Eloquent;

class UserActivity extends Eloquent {

	/** Database connection to use */
	protected $connection = 'vitaldev';
	/** Table to use */
	protected $table = 'User_Activity';
	/** primaryKey is objectID */
	protected $primaryKey = 'activityID';
	/** Allow DB to increment PK */
	public $incrementing = false;

	/**
	 * Table Structure
	 * desc User_Activity;
    +------------+---------------------+------+-----+---------------------+----------------+
    | Field      | Type                | Null | Key | Default             | Extra          |
    +------------+---------------------+------+-----+---------------------+----------------+
    | activityID | bigint(20) unsigned | NO   | PRI | NULL                | auto_increment |
    | id         | bigint(20)          | NO   |     | NULL                |                |
    | classID    | varchar(85)         | NO   |     | NULL                |                |
    | User_Name  | varchar(85)         | NO   |     | NULL                |                |
    | created_at | timestamp           | NO   |     | 0000-00-00 00:00:00 |                |
    | updated_at | timestamp           | NO   |     | 0000-00-00 00:00:00 |                |
    | Purpose    | varchar(85)         | NO   |     | NULL                |                |
    +------------+---------------------+------+-----+---------------------+----------------+
    7 rows in set (0.00 sec)
	 */

	/**
	 * Attributes that are mass-assignable during inserts
	 * @var array
	 */
	protected $fillable = array('id', 'classID', 'User_Name', 'Purpose');

	/**
	 * Attributes not mass-assignable during inserts
	 * @var array
	 */
	protected $guarded = array('activityID', 'created_at', 'updated_at');

	/*
	 * Field Mutators, used to validate, manipulate the value on set
	 * TODO Add get/set User_Name Attribute(..) - verify User_Name found in laravel.users.name
	 * Function name format is
	 * 'set' . CamileCase(fieldName) . 'Attribute'
	 * as in setNameAttribute() { .. }
	 */

}
