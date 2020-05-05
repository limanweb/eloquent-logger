<?php 

return [
    
    'user_id_type' => 'integer',

    'connection' => null,
    
    'exclude_fields' => [
        'created_at',
        'updated_at',
    ],
    
    'hidden_fields' => null,
    
    'models' => [
        \App\User::class => [
            'operations' => null,
            'excluded_fields' => null,
            'hidden_fields' => null,
        ],
    ],
    
];