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
        Log::debug(__METHOD__."(".__LINE__."):  ",$request);
        // get the previous request
        $previousRequest = [];
        if(Session::has('previousRequest')) {
            $previousRequest = Session::get('previousRequest');
        }
        // get previousRequest for this classID
        if(isset($previousRequest[$classID])) {
            $thisClassRequest = $previousRequest[$classID];
            $this->filterPreviousRequest($thisClassRequest);
            Log::debug(__METHOD__."(".__LINE__."):  ",$thisClassRequest);
            $request = array_merge($thisClassRequest, $request);
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
        Log::debug(__METHOD__."(".__LINE__."):  ",$request);
        //dd(__METHOD__."(".__LINE__.")",compact('previousRequest','request'));
        // return our composite request
        return $request;
    }

    protected function filterPreviousRequest(&$previousRequest) {
        unset($previousRequest['_method']);
        unset($previousRequest['_token']);
    }

    protected function defaultRequest() {
        $defaultRequest = [];
        // lets provide a default filter
        // $defaultRequest[' .. '] = ..;
        return $defaultRequest;
    }

}
