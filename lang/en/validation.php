<?php

// lang/en/validation.php
return [
    // ... other validation messages ...

    'custom' => [
        'email' => [
            'unique' => 'This contact email address is already registered by another member.',
        ],
        'phone_number' => [
            'unique' => 'This phone number is already in use by another member.',
        ],
        'national_id' => [
            'unique' => 'This National ID is already registered.',
        ],
    ],

    'attributes' => [
        'member_category_id' => 'membership category',
        // ... other attribute names ...
    ],
];