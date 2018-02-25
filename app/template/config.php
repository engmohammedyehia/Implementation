<?php

return [
    'template' => [
        ':view'             => ':action_view'
    ],
    'header_resources' => [
        'css' => [
            'normalize'         => CSS . 'normalize.css',
            'fawsome'           => CSS . 'fawsome.min.css',
            'gicons'            => CSS . 'googleicons.css',
            'main'              => CSS . 'main.' . $_SESSION['lang'] . '.css',
            'form'              => CSS . 'form.' . $_SESSION['lang'] . '.css'
        ],
        'js' => [
            'modernizr'         => JS . 'vendor/modernizr-2.8.3.min.js'
        ]
    ],
    'footer_resources' => [
        'jquery'                => JS . 'vendor/jquery-1.12.0.min.js',
        'helper'                => JS . 'helper.js',
        'main'                  => JS . 'main.js'
    ]
];