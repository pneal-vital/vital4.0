<?php namespace App\vital3;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Eloquent;
use \Log;

class Inventory extends Eloquent {

    const CONNECTION_NAME = 'vitaldev';
    const TABLE_NAME      = 'Inventory';

	/** Database connection to use */
	protected $connection = self::CONNECTION_NAME;
	/** Table to use */
	protected $table = self::TABLE_NAME;
	/** primaryKey is objectID */
	protected $primaryKey = 'objectID';
	/** Allow DB to increment objectID */
	public $incrementing = false;
	/** Does the table have laravel style timestamp fields */
	public $timestamps = false;
	/** created at column. */
	const CREATED_AT = 'Created';
	/** Attributes that are dates. */
	protected $dates = ['Created'];

	/**
	 * Table Structure
	 * desc Inventory;
    +------------+-------------+------+-----+---------+-------+
    | Field      | Type        | Null | Key | Default | Extra |
    +------------+-------------+------+-----+---------+-------+
    | objectID   | bigint(20)  | NO   | PRI | NULL    |       |
    | Item       | varchar(85) | YES  | MUL | NULL    |       | => Item.objectID (UPC)
    | Quantity   | varchar(85) | YES  |     | NULL    |       |
    | Created    | varchar(85) | YES  |     | NULL    |       |
    | Status     | varchar(85) | YES  | MUL | NULL    |       |
    | Order_Line | varchar(85) | YES  | MUL | NULL    |       | => Inbound_Order | Outbound_Order.objectID
    | UOM        | varchar(85) | YES  |     |         |       | => UOM.objectID
    +------------+-------------+------+-----+---------+-------+
    7 rows in set (0.01 sec)
	 */

	/**
	 * Attributes that are mass-assignable during inserts
	 * @var array
	 */
	protected $fillable = ['Item', 'Quantity', 'Status', 'Order_Line', 'UOM'];

	/**
	 * Attributes not mass-assignable during inserts
	 * @var array
	 */
	protected $guarded = ['objectID', 'Created'];

    /**
     * protected fields vs Field Mutators to manage _Additional information about this object
     * see: http://stackoverflow.com/questions/24637553/laravel-eloquent-orm-save-returns-column-not-found-mysql-error
     */
    public $Item_type;
    public $Item_typeID;
    public $Item_description;
    public $Order_Line_type;
    public $Order_Line_typeID;
    /**
     * Array to hold _Additional data items
    public $additional = [];
     */

	/**
	 * This function can set objectID, set default values, and validate the entered field values.
	 *
	 * Register this function in an Event Listener, see: http://laravel.com/docs/master/events
	 * or call it from EventServiceProvider::boot(..)
	 */
	public function isCreating() {
		// set objectID
        $inserted = VitalObject::create(['classID' => 'Inventory']);
        $this->objectID = $inserted->objectID;
        Log::debug('objectID: '.$this->objectID);

        // set default values
        $this->Status = "RECD";
        //Log::debug('Status: '.$this->Status);
        $now = Carbon::now();
        //Log::debug('now: '.$now);
        $this->Created = $now;
        //Log::debug('Created: '.$this->Created);

		/* validate the entered field values.
		if ( ! $this->isValid()) return false;
		*/
	}

    /**
     * This function is invoked before saving, (includes, create and update)
     * See; EventServiceProvider::boot(..) ..::saving(..)
     */
    public function isSaving() {
        $this->Item       = trim($this->Item       );
        $this->Quantity   = trim($this->Quantity   );
        $this->Created    = trim($this->Created    );
        $this->Status     = trim($this->Status     );
        $this->Order_Line = trim($this->Order_Line );
        $this->UOM        = trim($this->UOM        );
    }


    /**
     * Field Mutators, used to validate, manipulate the value on set
     * TODO Add get/set Client Attribute(..) - retrieve the objectID from Client
     * TODO Add get/set Status Attribute(..) - value must be valid, see STD.
     * Function name format is
     * 'set' . CamileCase(fieldName) . 'Attribute'
     * as in setNameAttribute() { .. }
     */
    public function getCreatedAttribute() {
        $result = Carbon::create(1901,01,01,00,00,00);
        if(isset($this->attributes['Created']) && strlen($this->attributes['Created']) == 19) {
            $result = Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['Created']);
        } elseif(isset($this->attributes['Created']) && strlen($this->attributes['Created']) == 16) {
            $result = Carbon::createFromFormat('Y-m-d H:i', $this->attributes['Created']);
        }
        return $result;
    }

    public function setCreatedAttribute($value) {
        // Consider using Carbon::parse($value);
        $created = Carbon::now();
        if(isset($value) && strlen($value) == 10) {
            $created = Carbon::createFromFormat('Y-m-d', $value)->format("Y-m-d H:i:s");
        } elseif(isset($value) && strlen($value) == 16) {
            $created = Carbon::createFromFormat('Y-m-d H:i', $value)->format("Y-m-d H:i:s");
        } elseif(isset($value) && strlen($value) == 19) {
            $created = Carbon::createFromFormat('Y-m-d H:i:s', $value)->format("Y-m-d H:i:s");
        }
        $this->attributes['Created'] = $created;
    }

    /**
     * Using Field Mutators to manage _Additional information about this object
    public function getItemTypeAttribute() {
        if(isset($this->additional['Item_type']) == false) return null;
        return $this->additional['Item_type'];
    }
    public function setItemTypeAttribute($value) {
        $this->additional['Item_type'] = $value;
    }
    public function getItemTypeIDAttribute() {
        return $this->additional['Item_typeID'];
    }
    public function setItemTypeIDAttribute($value) {
        $this->additional['Item_typeID'] = $value;
    }
    public function getItemDescriptionAttribute() {
        return $this->additional['Item_description'];
    }
    public function setItemDescriptionAttribute($value) {
        $this->additional['Item_description'] = $value;
    }
    public function getOrderLineTypeAttribute() {
        if(isset($this->additional['Order_Line_type']) == false) return null;
        return $this->additional['Order_Line_type'];
    }
    public function setOrderLineTypeAttribute($value) {
        $this->additional['Order_Line_type'] = $value;
    }
    public function getOrderLineTypeIDAttribute() {
        return $this->additional['Order_Line_typeID'];
    }
    public function setOrderLineTypeIDAttribute($value) {
        $this->additional['Order_Line_typeID'] = $value;
    }
     */

	/*
	 * Query Scope, when query building, this type of method is used to build the common parts
	 * Function name format is
	 * 'scope' . capitalize(functionName)
	 * If elsewhere, (possibly our controller) we often have something like this
	 * Inventory::where('Status', '=', 'NEW')->get();
	 * it can be replaced with Inventory::isNew()->get();
	 * and we can write the scope function as
	 */
    public function scopeIsReceived($query) {
        $query->where('Status', '=', 'RECD');
    }

    public function scopeIsReplen($query) {
        $query->whereStatus('REPLEN', 'A-REPLEN');
    }

    public function scopeIsOpen($query) {
        $query->whereStatus('OPEN', 'REPLEN');
    }

    public function scopeIsAllocated($query) {
        $query->whereStatus('ALLOC', 'A-REPLEN');
    }

    /**
     * in Model.php
     * :1433  public function update(array $attributes = [])
     */
    public function __update(array $attributes = [])
    {
        if (! $this->exists) {
            return $this->newQuery()->update($attributes);
        }

        $invAttributes = $this->attributes;
        $invAdditional = $this->additional;
        dd(__METHOD__."(".__LINE__.")",compact('attributes','invAttributes','invAdditional'));

        return $this->fill($attributes)->save();
    }

    /**
     * in Model.php
     * :411  fill(array $attributes)
     */
    public function __fill(array $attributes)
    {
        $totallyGuarded = $this->totallyGuarded();

        if(count($attributes)) {
            $invAttributes = $this->attributes;
            $invFillableFromArray = $this->fillableFromArray($attributes);
            $invIsFillable = $this->isFillable('Item_type');
            dd(__METHOD__."(".__LINE__.")",compact('attributes','totallyGuarded','invAttributes','invFillableFromArray','invIsFillable'));
        }

        foreach ($this->fillableFromArray($attributes) as $key => $value) {
            $key = $this->removeTableFromKey($key);

            // The developers may choose to place some attributes in the "fillable"
            // array, which means only those attributes may be set through mass
            // assignment to the model, and all others will just be ignored.
            if ($this->isFillable($key)) {
                $this->setAttribute($key, $value);
            } elseif ($totallyGuarded) {
                throw new MassAssignmentException($key);
            }
        }

        return $this;
    }
}
