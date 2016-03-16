<?php namespace vital40;

use Illuminate\Database\Eloquent\Model as Eloquent;

class User extends Eloquent {

	/** Database connection to use */
	//protected $connection = 'laravel'; <== laravel is the default
	/** Table to use */
	protected $table = 'users';
	/** primaryKey is objectID */
	protected $primaryKey = 'id';
	/** Allow DB to increment PK */
	public $incrementing = true;

	/**
	 * Table Structure
	 * desc users;
    +----------------+------------------+------+-----+---------------------+----------------+
    | Field          | Type             | Null | Key | Default             | Extra          |
    +----------------+------------------+------+-----+---------------------+----------------+
    | id             | int(10) unsigned | NO   | PRI | NULL                | auto_increment |
    | name           | varchar(255)     | NO   |     | NULL                |                |
    | email          | varchar(255)     | NO   | UNI | NULL                |                |
    | password       | varchar(60)      | NO   |     | NULL                |                |
    | remember_token | varchar(100)     | YES  |     | NULL                |                |
    | created_at     | timestamp        | NO   |     | 0000-00-00 00:00:00 |                |
    | updated_at     | timestamp        | NO   |     | 0000-00-00 00:00:00 |                |
    +----------------+------------------+------+-----+---------------------+----------------+
    7 rows in set (0.00 sec)
	 */

	/**
	 * Attributes that are mass-assignable during inserts
	 * @var array
	 */
	protected $fillable = array('name', 'email');

	/**
	 * Attributes not mass-assignable during inserts
	 * @var array
	 */
	protected $guarded = array('id', 'password', 'remember_token', 'created_at', 'updated_at');

	/**
	 * This function can set default values, and validate the entered field values.
	 *
	 * Register this function in an Event Listener, see: http://laravel.com/docs/master/events
	 * or call it from EventServiceProvider::boot(..)
	 */
	public function isCreating()
	{
		// set default values
		$this->password = '$2y$10$Rhx8KgIixYilXZKHyi7ie.gXzihaXYL90g8Hwow8PwvJq4GdQq4mO';
	}

}
