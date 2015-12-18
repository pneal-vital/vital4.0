<?php namespace App\vital3;

use Illuminate\Database\Eloquent\Model as Eloquent;

class ItemAdditional extends Eloquent {

	/** Database connection to use */
	protected $connection = 'vitaldev';
	/** Table to use */
	protected $table = 'Item_Additional';
	/** primaryKey is objectID */
	protected $primaryKey = 'objectID';
	/** Allow DB to increment objectID */
	public $incrementing = false;
	/** Does the table have laravel style timestamp fields */
	public $timestamps = false;

	/**
	 * Table Structure
	 * desc Item_Additional;
	+----------+-------------+------+-----+---------+-------+
	| Field    | Type        | Null | Key | Default | Extra |
	+----------+-------------+------+-----+---------+-------+
	| objectID | bigint(20)  | NO   | PRI | NULL    |       |
	| Name     | varchar(85) | NO   | PRI | NULL    |       |
	| Value    | text        | NO   |     | NULL    |       |
	+----------+-------------+------+-----+---------+-------+
	3 rows in set (0.00 sec)
	 */

	/**
	 * Attributes that are mass-assignable during inserts
	 * @var array
	 */
	protected $fillable = array('objectID', 'Name', 'Value');

    /**
     * Hack to add Name into where clause of update statement
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function setKeysForSaveQuery(\Illuminate\Database\Eloquent\Builder $query)
    {
        parent::setKeysForSaveQuery($query);
        $query->where('Name', '=', $this->attributes['Name']);
        //dd(__METHOD__.'('.__LINE__.')',compact('query'));
        return $query;
    }

}
