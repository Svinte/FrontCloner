<?php

return [
    'hosts' => [
        /**
         * Allow cloning from all domains. (not recommended)
         */
        'allow_all' => false,

        /**
         * List of all allowed URLs.
         */
        'whitelist' => [],

        /**
         * List of all blocked URLs.
         */
        'blacklist' => [],
    ],

    'cache' => [
        /**
         * Cached file id length.
         */
        'id_len' => 9,

        /**
         * Dir cache directory.
         */
        'dir' => storage_path('app/vendor/frontcloner/'),

        /**
         * Cache map filename.
         */
        'map' => 'map.json',
    ]
]