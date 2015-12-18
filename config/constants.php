<?php

/*
|--------------------------------------------------------------------------
| Place in this file all your application constants
|--------------------------------------------------------------------------
|
| Access constants using Config::get('constants.inventory.received');
| See: http://stackoverflow.com/questions/26854030/laravel-where-to-store-global-arrays-data-and-constants
|
*/

return [

    /*
     * Application
     */
    'application' => [
        'name' => 'VITaL4',
    ],

    /*
     * Generated Events
     */
    'event' => [
        '302' => [
            'eventID' => '302',
            'priority' => '10',
            'reasonCode' => [
                'vital_HH' => 'HH',
            ],
        ],
    ],

    /*
     * Inventory
     */
    'inventory' => [

        /*
         * Inventory Status Codes
         *
         * State Transition:
          -> received -> putAway -> open -> replen -> (and back to) open
                                                   -> allocated_replen -> (and back to) Allocated
                                         -> allocated -> allocated_replen -> (and back to) Allocated
                                                      -> allocated_printed -> picked -> packed -> loaded -> shipped.
         */
        'status' => [

            'received'                => 'RECD',
            'putAway'                 => 'PUTAWAY',
            'open'                    => 'OPEN',
            'replen'                  => 'REPLEN',
            'allocated'               => 'ALLOC',
            'allocated_replen'        => 'A-REPLEN',
            'allocated_printed'       => 'ALLOCG',
            'allocated_printed_repen' => 'AG-REPLEN',
            'picked'                  => 'TPICK',
            'packed'                  => 'PACKED',
            'loaded'                  => 'LOADED',
            'shipped'                 => 'SHIPPED',
        ],

        /*
         * Order_Line - points to an Inbound_Order_Detail.objectID or an Outbound_Order_Detail.objectID
         */
        'orderLine' => [
            'pointsTo' => [
                'inbound'   => 'PurchaseOrderDetail',
                'outbound'  => 'OutboundOrderDetail',
            ],
        ],

    ],

    /*
     * itemKit
     */
    'itemKit' => [

        /*
         * objectID - points to a UPC
         * parentID - points to an Article
         */
        'objectID' => [
            'pointsTo' => 'UPC',
        ],
        'parentID' => [
            'pointsTo' => 'Article',
        ],

    ],

    /*
     * Location
     */
    'location' => [

        /*
         * Location Status Codes
         *
         * State Transition:
          -> open
         */
        'status' => [
            'open'    => 'OPEN',
        ],
    ],

    /*
     * Pallet
     */
    'pallet' => [

        /*
         * Pallet Status Codes
         *
         * State Transition:
          -> lock -> open
          -> open -> loaded -> shipped
                  -> putAway -> open
         */
        'status' => [
            'lock'    => 'LOCK',
            'open'    => 'OPEN',
            'putAway' => 'PUTAWAY',
            'loaded'  => 'LOADED',
            'shipped' => 'SHIPPED'
        ],
    ],

    /*
     * PurchaseOrder
     */
    'purchaseOrder' => [

        /*
         * PurchaseOrder Status Codes
         *
         * State Transition:
          -> open -> receiving -> confirmed
         */
        'status' => [
            'open'      => 'OPEN',
            'receiving' => 'REC',
            'confirmed' => 'CONF',
        ],
    ],

    /*
     * PurchaseOrderDetail
     */
    'purchaseOrderDetail' => [

        /*
         * PurchaseOrderDetail Status Codes
         *
         * State Transition:
          -> open -> receiving -> confirmed
         */
        'status' => [
            'open'      => 'OPEN',
            'receiving' => 'REC',
            'confirmed' => 'CONF',
        ],
    ],

    /*
     * Class to Route Names
     */
    'routeName' => [

        /*
         * class names
         * TODO OutboundOrderDetail should point to ood.show once ood is created, and correct inventory/list.blade.php
         */
        'OutboundOrderDetail' => [
            'show'  => 'pod.show',
        ],
        'POReconciliation' => [
            'show'  => 'poReconciliation.show',
        ],
        'PurchaseOrderDetail' => [
            'show'  => 'pod.show',
        ],
        'ReceivePO' => [
            'show'  => 'receivePO.show',
        ],
        'ReceiveArticle' => [
            'show'  => 'receiveArticle.show',
        ],
        'ReceiveLocation' => [
            'show'  => 'receiveLocation.show',
        ],
        'UPC' => [
            'show'  => 'upc.show',
        ],
    ],

    /*
     * Tote
     */
    'tote' => [

        /*
         * Tote Status Codes
         *
         * State Transition:
          received -> putAway -> open -> loaded -> (back to) open
         */
        'status' => [
            'received' => 'RECD',
            'putAway'  => 'PUTAWAY',
            'open'     => 'OPEN',
            'loaded'   => 'LOADED',
        ],
    ],

    /*
     * userActivity
     */
    'userActivity' => [

        /*
         * classID
         */
        'classID' => [
            'POReconciliation' => 'POReconciliation',
            'ReceiveArticle'   => 'ReceiveArticle',
            'ReceiveLocation'  => 'ReceiveLocation',
            'ReceivePO'        => 'ReceivePO',
        ],

        /*
         * objectID - points to a PurchaseOrder
         *          - points to an Article
         *          - points to a Location
         */
        'objectID' => [
            'pointsTo' => [
                'Inbound_Order'  => 'ReceivePO',
                'Item'           => 'ReceiveArticle',
                'Location'       => 'ReceiveLocation',
            ],
        ],

    ],

];
