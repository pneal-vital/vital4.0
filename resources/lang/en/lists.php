<?php

return [

    /*
     * Article
     */
    'article' => [

        /*
         * rework values
         */
        'rework' => [
            'none'   => 'None',
            'low'    => 'Low',
            'medium' => 'Medium',
            'high'   => 'High',
        ],

        /*
         * split values
         */
        'split' => [
            'N'         => 'Comingle',
            'Y'         => 'Split',
            ''          => 'Split',
        ],
    ],

    /*
     * InboundOrder
     */
    'inboundOrder' => [

        /*
         * Status values
         */
        'status' => [
            'OPEN'      => 'Open',
            'REC'       => 'Receiving',
            'CONF'      => 'Confirmed',
        ],
    ],

    /*
     * InboundOrderDetail
     */
    'inboundOrderDetail' => [

        /*
         * Status values
         */
        'status' => [
            'OPEN'      => 'Open',
            'REC'       => 'Receiving',
            'OVERREC'   => 'Over Received',
            'CONF'      => 'Confirmed',
        ],
    ],

    /*
     * Inventory
     */
    'inventory' => [

        /*
         * Status values
         */
        'status' => [
            'ALLOC'     => 'Allocated',
            'ALLOCG'    => 'Allocated, Printed',
            'AG-REPLEN' => 'Allocated, Printed, Replenishment',
            'A-REPLEN'  => 'Allocated, Replenishment',
            'LOADED'    => 'Loaded',
            'OPEN'      => 'Open',
            'PACKED'    => 'Packed',
            'PUTAWAY'   => 'Put Away',
            'RECD'      => 'Received',
            'REPLEN'    => 'Replenishment',
            'SHIPPED'   => 'Shipped',
            'TPICK'     => 'Picked',
        ],
    ],

    /*
     * Inventory Summary
     */
    'invSummary' => [

        /*
         * Pick face quantity choices
         */
        'pickQty' => [
            'any'          => 'any',
            'zero'         => '0 in Pick Face',
            'belowMin'     => 'Less than minimum (3)',
            'aboveMin'     => 'Greater than minimum',
        ],

        /*
         * Activity location quantity choices
         */
        'actQty' => [
            'any'          => 'any',
            'zero'         => '0 in Activity Locations',
            'aboveZero'    => 'Greater than 0',
        ],

        /*
         * Reserve quantity choices
         */
        'resQty' => [
            'any'          => 'any',
            'zero'         => 'none in Reserve',
            'aboveZero'    => 'Have Reserve',
        ],

        /*
         * Replen Priority choices
         */
        'replenPrty' => [
            'noReplen'     => 'No replens',
            '20orBelow'    => 'Replen Priority &nbsp;1 - 20',
            '40orBelow'    => 'Replen Priority 21 - 40',
            '60orBelow'    => 'Replen Priority 41 - 60',
        ],
    ],

    /*
     * Pallet
     */
    'pallet' => [

        /*
         * Status values
         */
        'status' => [
            'LOCK'    => 'Locked',
            'PUTAWAY' => 'Put Away',
            'OPEN'    => 'Open',
            'SHIPPED' => 'Shipped',
            'LOADED'  => 'Loaded',
        ],
    ],

    /*
     * PurchaseOrder
     */
    'purchaseOrder' => [

        /*
         * Status values
         */
        'status' => [
            'OPEN'   => 'Open',
            'REC'    => 'Receiving',
            'CONF'   => 'Confirmed',
        ],
    ],

    /*
     * Generic_Container
     */
    'tote' => [

        /*
         * Status values
         */
        'status' => [
            'OPEN'    => 'Open',
            'RECD'    => 'Received',
            'REPLEN'  => 'Replen',
            'LOADED'  => 'Loaded',
            'PUTAWAY' => 'Put Away',
        ],
    ],

    /*
     * UOM - Unit Of Measure
     */
    'uom' => [

        /*
         * uom.<columnName>
         */
        'Uom' => [
            'unknown'  => 'Unknown',
            'CS'       => 'Case',
            'DZ'       => 'Dozens',
            'EA'       => 'Eaches',
            'SKU'      => 'SKU as uom??',
            'ST'       => 'ST as uom??',
        ],
    ],
];