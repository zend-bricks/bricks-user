<?php
return [
    'default' => [
        'user' => [
            'label' => 'user',
            'route' => 'home',
            'order' => 800,
            'pages' => [
                [
                    'label' => 'profile',
                    'route' => 'profile/show',
                    'resource' => 'profile/show',
                    'order' => 100
                ],
                [
                    'label' => 'login',
                    'route' => 'auth/login',
                    'resource' => 'auth/login',
                    'order' => 800
                ],
                [
                    'label' => 'register',
                    'route' => 'auth/register',
                    'resource' => 'auth/register',
                    'order' => 900
                ],
                [
                    'label' => 'logout',
                    'route' => 'auth/logout',
                    'resource' => 'auth/logout',
                    'order' => 900
                ],
            ]
        ],
        'admin' => [
            'label' => 'admin',
            'route' => 'home',
            'order' => 700,
            'pages' => [
                [
                    'label' => 'user',
                    'route' => 'user/list',
                    'resource' => 'user/list',
                    'order' => 100
                ],
                [
                    'label' => 'role',
                    'route' => 'role/list',
                    'resource' => 'role/list',
                    'order' => 200
                ],
            ]
        ]
    ]
];
