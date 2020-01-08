<?php

return [

    // Can be overridden via Settings DB table

    'avatar' => [
        'max_size' => 2 * 1024, // Кб
    ],

    'article' => [
        'image' => [
            'max_size' => 5 * 1024, // Кб
        ],
    ],

    'paginator' => [
        'items' => 10,
    ],

    'navbar' => [
        'articles' => 4,
    ],

    'custom_paginator' => [
        'items' => 10,
        'options' => ['10' => '10', '20' => '20', '50' => '50', '200' => '200', 'Все' => 'all'],
    ],
];
