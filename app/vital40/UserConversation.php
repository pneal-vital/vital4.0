<?php namespace vital40;

use Illuminate\Database\Eloquent\Model as Eloquent;

class UserConversation extends Eloquent {

	/** Database connection to use */
	protected $connection = 'devaudit';
	/** Table to use */
	protected $table = 'User_Conversation';
	/** primaryKey is objectID */
	protected $primaryKey = 'activityID';
	/** Allow DB to increment PK */
	public $incrementing = false;

	/**
	 * Table Structure
	 * desc User_Conversation;
    +-------------+---------------------+------+-----+---------------------+----------------+
    | Field       | Type                | Null | Key | Default             | Extra          |
    +-------------+---------------------+------+-----+---------------------+----------------+
    | activityID  | bigint(20) unsigned | NO   | PRI | NULL                | auto_increment |
    | POD         | bigint(20)          | NO   |     | NULL                |                |
    | Article     | bigint(20)          | NO   |     | NULL                |                |
    | User_Name   | varchar(85)         | NO   |     | NULL                |                |
    | Sender_Name | varchar(85)         | NO   |     | NULL                |                |
    | created_at  | timestamp           | NO   |     | 0000-00-00 00:00:00 |                |
    | updated_at  | timestamp           | NO   |     | 0000-00-00 00:00:00 |                |
    | Text        | text                | NO   |     | NULL                |                |
    | error       | text                | NO   |     | NULL                |                |
    +-------------+---------------------+------+-----+---------------------+----------------+
    8 rows in set (0.01 sec)
	 */

	/**
	 * Attributes that are mass-assignable during inserts
	 * @var array
	 */
	protected $fillable = array('POD', 'Article', 'User_Name', 'Sender_Name', 'Text', 'error');

	/**
	 * Attributes not mass-assignable during inserts
	 * @var array
	 */
	protected $guarded = array('activityID', 'created_at', 'updated_at');

}
