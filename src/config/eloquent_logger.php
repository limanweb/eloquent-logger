<?php 

return [
    
    'user' => [
        'model' => \App\User::class,
        'key_cast' => 'int',
        'key_create_method' => 'bigInteger',
    ],

    // Changes of this fields not logged
    'exclude_fields' => [
        'created_at',
        'updated_at',
    ],

    'models' => [
        \App\User::class => [
            'exclude_fields' => null,
        ],
    ],
    
];