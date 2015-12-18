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

        'deleteHasChildren'    => 'Cannot delete an :Model that has :Children.',

        'location' => [
            'alreadyInUse'     => ':Work_Table is already in use by :User_Name, Select a different Work Table',
            'alreadyOpen'      => 'Article still has open totes at :Location_Name, Cannot change work table',
            'hasOpen'          => ':Work_Table has open totes, Select a different Work Table',
            'notValidLocation' => ':Work_Table is not a unique location, click Select a Work Table',
        ],

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