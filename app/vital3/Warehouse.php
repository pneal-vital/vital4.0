<?php namespace App\vital3;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Warehouse extends Eloquent {

	/** Database connection to use */
	protected $connection = 'vitaldev';
	/** Table to use */
	protected $table = 'Warehouse';
	/** primaryKey column */
	protected $primaryKey = 'objectID';
	/** Allow DB to increment $primaryKey */
	public $incrementing = false;
	/** Does the table have laravel style timestamp fields */
	public $timestamps = false;

	/**
	 * Table Structure
	 * desc Warehouse;
    +----------------+-------------+------+-----+---------+-------+
    | Field          | Type        | Null | Key | Default | Extra |
    +----------------+-------------+------+-----+---------+-------+
    | objectID       | bigint(20)  | NO   | PRI | NULL    |       |
    | Warehouse_Code | varchar(85) | YES  |     | NULL    |       |
    | Warehouse_Name | varchar(85) | YES  |     | NULL    |       |
    | Address_1      | varchar(85) | YES  |     | NULL    |       |
    | Address_2      | varchar(85) | YES  |     | NULL    |       |
    | City           | varchar(85) | YES  |     | NULL    |       |
    | Province       | varchar(85) | YES  |     | NULL    |       |
    | Post_Code      | varchar(85) | YES  |     | NULL    |       |
    | Phone          | varchar(85) | YES  |     | NULL    |       |
    | Fax            | varchar(85) | YES  |     | NULL    |       |
    | Remote_Address | varchar(85) | YES  |     | NULL    |       |
    +----------------+-------------+------+-----+---------+-------+
    11 rows in set (0.02 sec)
     */

	/**
	 * Attributes that are mass-assignable during inserts
	 * @var array
	 */
	protected $fillable = array('Warehouse_Code', 'Warehouse_Name', 'Address_1', 'Address_2', 'City', 'Province', 'Post_Code', 'Phone', 'Fax', 'Remote_Address');

	/**
	 * Attributes not mass-assignable during inserts
	 * @var array
	 */
	protected $guarded = array('objectID');


}
