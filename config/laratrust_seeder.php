<?php

return [
    /**
     * Control if the seeder should create a user per role while seeding the data.
     */
    'create_users' => true,

    /**
     * Control if all the laratrust tables should be truncated before running the seeder.
     */
    'truncate_tables' => true,

    'roles_structure' => [
        'superadministrator' => [
            'users' => 'c,r,u,d',
            'profile' => 'r,u',
        ],
        'administrator' => [
            'profile' => 'r,u',
            'products' => 'c,r,u,d,sp,ns',
            'sales' => 'tp',
        ],
        'editor' => [
            'profile' => 'r,u',
            'products' => 'c,r,u,d',
        ],
        'user' => [
            'profile' => 'r,u',
            'products' => 'r,p',
        ],
    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete',
        'p' => 'purchase',
        'sp' => 'sold-products',
        'ns' => 'no-stock',
        'tp' => 'total-profit',
    ],
];
