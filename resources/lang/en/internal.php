<?php

return [

    /*
     * ArticleFlow
     */
    'articleFlow' => [

        /*
         * clicked a button with no text entered
         */
        'clicked' => [
            'btn-receive-upc'   => 'Receiving UPCs into Totes, - Scan a UPC',
            'btn-close-tote'    => 'Placing Totes onto Carts, - Scan a tote',
            'btn-entry'         => 'What would you like to do?',
            'text_entry'        => 'What would you like to do?',
        ],

        'closeTote'             => 'Close Tote, Tote# :cartonID, onto Cart# :palletID - Close another tote ..',
        'forcedOutOfReceiving'  => 'Forced out of receiving by :super',
        'leaveReceiving'        => 'Signed out of receiving',
        'putPalletIntoLocation' => 'Added cart :palletID to work table :locationID',
        'putUPCinTote'          => 'Received UPC# :previousText into Tote# :cartonID - Scan another UPC',
        'scannedTote'           => 'Tote :Text Scanned, :cartonID - Scan a cart',
        'scannedUPC'            => 'UPC :Text Scanned, :Description - Scan a tote',
        'scannedUPCinTote'      => 'UPC :Text Scanned, :Description - Place in tote :cartonID',
        'selectArticle'         => 'Selected Article :Article',
        'selectLocation'        => 'Selected work table :Work_Table',
        'selectPO'              => 'Selected Purchase Order :Purchase_Order',
        'selectRework'          => 'Set Article :Article, rework value :Rework',
        'unknown'               => "Don't understand your entry",
    ],

    'created' => ':class created successfully.',

    /*
     * errors messages
     */
    'errors' => [

        'article' => [
            'alreadyInUse'     => 'Article :Article is already in use by :User_Name, Select a different Article',
            'alreadyOpen'      => 'Article :Article has open totes at :Location_Name, Select a different Article',
            'noArticle'        => 'Establish an Article first.',
            'notFound'         => 'Article :Article is not found',
            'notOfThisPO'      => 'Article :Article or UPC is not from Purchase Order :Purchase_Order',
            'openTotes'        => 'Article :Article has open tote :ToteID in :Location_Name',
        ],

        'comingleRules'  => [
            'breaks'           => 'Cannot perform this move, it breaks comingling rules',
            'notMoveComingle'  => 'Cannot move :invComingled inventory in with the inventory in tote :Carton_ID',
            'notMoveLocType'   => 'Cannot move from :from into :to location',
            'palletController' => 'ERROR: Pallet Controller is not available',
            'reserve'          => 'Cannot move this into a Reserve location',
            'notSupported'     => 'Comingling rules not yet supported in this module',
        ],

        'deleteHasChildren'    => 'Cannot delete an :Model that has child :Children.',
        'deleteHasParent'      => 'Cannot delete an :Model connected to parent :Parent.',

        'export' => [
            'unsupportedType'  => 'ERROR: Unsupported export type :exportType',
        ],

        'location' => [
            'alreadyInUse'     => ':Work_Table is already in use by :User_Name, Select a different Work Table',
            'alreadyOpen'      => 'Article still has open totes at :Location_Name, Cannot change work table',
            'hasOpen'          => ':Work_Table has open totes, Select a different Work Table',
            'notValidLocation' => ':Work_Table is not a unique location, click Select a Work Table',
        ],

        'noParent'             => 'Warning: This object is not located!',
        'notAuthorized'        => 'Not authorized to :action a :object of :id',
        'notFound'             => 'Error: :classID :objectID is not found.',

        'pallet' => [
            'notFound'         => 'Error: Scanned Cart :palletID is not found - Scan a Cart Label',
        ],

        'purchaseOrder' => [
            'noOpenPOs'        => 'No Status OPEN Purchase Orders were found with Article :Article or UPC :UPC',
            'notOpen'          => 'Purchase Order # :Purchase_Order is not found or not Status Open, Receiving',
            'openTotes'        => 'Purchase Order # :Purchase_Order has open tote :ToteID in this lane.',
        ],

        'tote' => [
            'alreadyPutAway'   => 'Error: Scanned Tote :tote is already on a Put Away cart :cart - Scan a different Tote',
            'anotherLocation'  => 'Error: Scanned Tote :tote is in another location :locationID - Scan a different Tote',
            'anotherOpenTote'  => 'Error: Cannot use scanned Tote :tote, already another open tote for this UPC - Scan a different Tote',
            'containsOtherUPC' => 'Error: Scanned Tote :tote contains UPC not commingling with :upcID - Please scan a different tote',
            'isEmpty'          => 'Error: Scanned Tote :tote is empty - Please scan a different tote',
        ],

        'upc' => [
            'notFound'         => 'UPC :UPC is not found',
            'notOfThisPO'      => 'UPC :UPC is not from Purchase Order :Purchase_Order',
            'notOfArticle'     => 'Error: UPC :Text is not from Article :article - Please scan another UPC',
            'alreadyInUse'     => 'Article of this UPC :UPC is already in use by :User_Name, Select a different Article/UPC',
            'alreadyOpen'      => 'Article of this UPC :UPC has open totes at :Location_Name, Select a different Article/UPC',
        ],
    ],

    /*
     * Job Names
     */
    'jobName' => [
        'reworkReport' => 'App\Jobs\ReworkReportJob',
    ],

    /*
     * Job Status
     */
    'jobStatus' => [
        'submitted'    => 'Rework Report job :id has been submitted.',
        'emailed'      => 'Rework Report job :id has completed and results have been emailed.',
    ],

    /*
     * Permission
     */
    'permission' => [
        'allows'       => 'The permission :display_name allows a user to :description.',
    ],

    /*
     * ReceiptHistory
     */
    'receiptHistory' => [
        'closeTote'    => 'Close Tote - :time, Tote# :cartonID, onto Cart# :palletID',
        'putUPCinTote' => 'Received UPC into Tote - :time, UPC# :upcSKU, Tote# :cartonID, (:n of :ofn)',
    ],

    /*
     * Role
     */
    'role' => [
        'allows'       => 'The role :display_name is a user authorized to :description.',
    ],

    /*
     * UOM - Unit Of Measure
     */
    'uom' => [

        /*
         * uom.<columnName>
         */
        'Uom' => [
            'case'  => 'CS',
            'dozen' => 'DZ',
            'each'  => 'EA',
            'sku'   => 'SKU',
            'st'    => 'ST',
        ],
    ],

    'updated' => ':class updated successfully.',

    /*
     * UserActivity
     */
    'userActivity' => [

        /*
         * UserActivity.Purpose
         */
        'purpose' => [
            'poReconciliation' => 'Reconcile Purchase Order :id',
            'receiveArticle'   => 'Receive Article UPC :id',
            'receiveLocation'  => 'Receive Location name :name',
            'receivePO'        => 'Receive PO # :id',
        ],
    ],
];