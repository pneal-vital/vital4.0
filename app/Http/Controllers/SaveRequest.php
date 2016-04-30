<?php namespace App\Http\Controllers;

use \Log;
use \Request;
use \Session;

/**
 * Trait saveRequest
 * @package App\Http\Controllers
 */
trait SaveRequest {

    /**
	 * Accept the current request, add in the previous filter request if we have one
	 */
    protected function getRequest($classID = 'Unknown') {
        // Get this request
        $request = Request::all();
        if(count($request) == 2 and isset($request['_method']) and isset($request['_token'])) {
            $request = [];
        }
        //Log::debug('  initial request:',$request);
        // get the previous request
        $previousRequest = [];
        if(Session::has('previousRequest')) {
            $previousRequest = Session::get('previousRequest');
        }
        // get previousRequest for this classID
        if(isset($previousRequest[$classID])) {
            $thisClassRequest = $previousRequest[$classID];
            $this->filterPreviousRequest($thisClassRequest);
            //Log::debug($classID.' request:',$thisClassRequest);
            $request = array_merge($thisClassRequest, $request);
            //dd(__METHOD__.'('.__LINE__.')',compact('thisClassRequest','previousRequest','request'));
        }
        // allow for a default Filter if we don't have one
        if(count($request) == 0) {
            // lets provide a default filter
            $request = $this->defaultRequest();
            if(isset($request) == False) $request = [];
        }
        // save request for next transaction
        $previousRequest[$classID] = $request;
        Session::put('previousRequest', $previousRequest);
        //Session::forget('PreviousRequest');
        //Log::debug(' combined request:',$request);
        //dd(__METHOD__.'('.__LINE__.')',compact('previousRequest','request'));
        // return our composite request
        return $request;
    }

    /**
     * Here we are removing keys from the previous request for this classID
     * @param $previousRequest
     */
    protected function filterPreviousRequest(&$previousRequest) {
        $unsetKeys = ['_method', '_token'];
        foreach($previousRequest as $key => $item) {
            if(strlen($key) > 4 and substr($key, 0, 4) == 'btn_') {
                $unsetKeys[] = $key;
            }
        }
        foreach($unsetKeys as $key) {
            unset($previousRequest[$key]);
        }
    }

    protected function defaultRequest() {
        $defaultRequest = [];
        // lets provide a default filter
        // $defaultRequest[' .. '] = ..;
        return $defaultRequest;
    }

}
