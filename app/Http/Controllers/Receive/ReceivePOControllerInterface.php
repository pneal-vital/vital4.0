<?php namespace App\Http\Controllers\Receive;



/**
 * Interface ReceivePOControllerInterface
 */
use PhpParser\Node\Stmt\Interface_;

interface ReceivePOControllerInterface {


	/**
	 * display the specific resource
	 */
	public function show($id);

}
