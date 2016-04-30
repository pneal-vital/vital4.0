<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use \Auth;
use \Log;
use \Session;

/**
 * Trait DBTransaction
 * see: http://fideloper.com/laravel-database-transactions
 *
 * @package App\Http\Controllers
 */
trait DBTransaction {

    protected $connection = 'laravel';

    /**
	 * Set the connection for including module
	 */
    protected function setConnection($connection = 'laravel') {
        $this->connection = $connection;
    }

    /**
	 * Begin a transaction, set @username and @functionName
	 */
    protected function transaction(\Closure $callback) {
        // begin the transaction
        DB::connection($this->connection)->beginTransaction();

        // Our own code to set @username and @functionName
        // on the appropriate connection
        $username = Auth::user()->name;
        DB::connection($this->connection)->statement("set @username = '".$username."'");

        $previousUrl = Session::previousUrl();
        Log::debug('Session::previousUrl(): '.$previousUrl);
        if(strpos($previousUrl, '?') > 0) {
            $previousUrl = substr($previousUrl, 0, strpos($previousUrl, '?'));
        }
        $previousUrl_parts = explode('/',$previousUrl);
        unset($previousUrl_parts[0]);       // remove http:
        unset($previousUrl_parts[1]);       // remove \\
        unset($previousUrl_parts[2]);       // remove localhost:8888\
        $functionName = implode('.',$previousUrl_parts);
        DB::connection($this->connection)->statement("set @functionName = '".$functionName."'");

        // We'll simply execute the given callback within a try / catch block
        // and if we catch any exception we can rollback the transaction
        // so that none of the changes are persisted to the database.
        try
        {
            $result = $callback($this);

            if(isset($result) == false or $result === true) {
                DB::connection($this->connection)->commit();
            } else {
                DB::connection($this->connection)->rollBack();
            }
        }

            // If we catch an exception, we will roll back so nothing gets messed
            // up in the database. Then we'll re-throw the exception so it can
            // be handled how the developer sees fit for their applications.
        catch (\Exception $e)
        {
            DB::connection($this->connection)->rollBack();

            throw $e;
        }

        return $result;
    }

}
