<?php namespace vital40;

use Illuminate\Database\Eloquent\Model as Eloquent;

class RoleUser extends Eloquent {

	/** Database connection to use */
	//protected $connection = 'laravel'; <== laravel is the default
	/** Table to use */
	protected $table = 'role_user';
	/** primaryKey is objectID */
	protected $primaryKey = ['user_id','role_id'];
	/** Allow DB to increment PK */
	public $incrementing = false;
    /** Does the table have laravel style timestamp fields */
    public $timestamps = false;

	/**
	 * Table Structure
	 * desc role_user;
	+---------+------------------+------+-----+---------+-------+
	| Field   | Type             | Null | Key | Default | Extra |
	+---------+------------------+------+-----+---------+-------+
	| user_id | int(10) unsigned | NO   | PRI | NULL    |       |
	| role_id | int(10) unsigned | NO   | PRI | NULL    |       |
	+---------+------------------+------+-----+---------+-------+
	2 rows in set (0.00 sec)
	 */

	/**
	 * Attributes that are mass-assignable during inserts
	 * @var array
	 */
	protected $fillable = array('user_id', 'role_id');

    /**
     * A permission_role points to a role
     */
    public function role()
    {
        return $this->belongsTo('vital40\Role');
    }

    /**
     * A permission_role points to a permission
     */
    public function user()
    {
        return $this->belongsTo('vital40\User');
    }

	/**
	 * Set the keys for a save update query.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	protected function setKeysForSaveQuery(\Illuminate\Database\Eloquent\Builder $query)
	{
		$query->where($this->primaryKey[0], '=', $this->getIndexedKeyForSaveQuery(0));
		$query->where($this->primaryKey[1], '=', $this->getIndexedKeyForSaveQuery(1));

		return $query;
	}

    /**
     * Get the primary key[Indexed] value for a save query.
     *
     * @return mixed
     */
    protected function getIndexedKeyForSaveQuery($index)
    {
        if (isset($this->original[$this->primaryKey[$index]])) {
            return $this->original[$this->primaryKey[$index]];
        }

        return $this->getAttribute($this->primaryKey[$index]);
    }

}
