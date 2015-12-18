<?php namespace App\vital3;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Client extends Eloquent {

	/** Database connection to use */
	protected $connection = 'vitaldev';
	/** Table to use */
	protected $table = 'Client';
	/** primaryKey column */
	protected $primaryKey = 'objectID';
	/** Allow DB to increment $primaryKey */
	public $incrementing = false;
	/** Does the table have laravel style timestamp fields */
	public $timestamps = false;

	/**
	 * Table Structure
	 * desc Client;
    +---------------+--------------+------+-----+---------+-------+
    | Field         | Type         | Null | Key | Default | Extra |
    +---------------+--------------+------+-----+---------+-------+
    | objectID      | bigint(20)   | NO   | PRI | NULL    |       |
    | Client_Name   | varchar(85)  | YES  |     | NULL    |       |
    | Address1      | varchar(255) | YES  |     | NULL    |       |
    | Address2      | varchar(255) | YES  |     | NULL    |       |
    | City          | varchar(85)  | YES  |     | NULL    |       |
    | Province      | varchar(85)  | YES  |     | NULL    |       |
    | Post_Code     | varchar(85)  | YES  |     | NULL    |       |
    | Contact_Name  | varchar(85)  | YES  |     | NULL    |       |
    | Contact_email | varchar(85)  | YES  |     | NULL    |       |
    | Contact_phone | varchar(85)  | YES  |     | NULL    |       |
    | Contact_fax   | varchar(85)  | YES  |     | NULL    |       |
    | Backup_Name   | varchar(85)  | YES  |     | NULL    |       |
    | Backup_email  | varchar(85)  | YES  |     | NULL    |       |
    | Backup_phone  | varchar(85)  | YES  |     | NULL    |       |
    | Backup_fax    | varchar(85)  | YES  |     | NULL    |       |
    +---------------+--------------+------+-----+---------+-------+
    15 rows in set (0.01 sec)
	 */

	/**
	 * Attributes that are mass-assignable during inserts
	 * @var array
	 */
	protected $fillable = array('Client_Name', 'Address1', 'Address2', 'City', 'Province', 'Post_Code'
        , 'Contact_Name', 'Contact_email', 'Contact_phone', 'Contact_fax'
        , 'Backup_Name', 'Backup_email', 'Backup_phone', 'Backup_fax');

	/**
	 * Attributes not mass-assignable during inserts
	 * @var array
	 */
	protected $guarded = array('objectID');

    /**
     * This function can set objectID, set default values, and validate the entered field values.
     *
     * Register this function in an Event Listener, see: http://laravel.com/docs/master/events
     * or call it from EventServiceProvider::boot(..)
     */
    public function isCreating() {
        // set objectID
        $inserted = VitalObject::create(['classID' => 'UOM']);
        $this->objectID = $inserted->objectID;
    }

}
