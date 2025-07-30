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
         * Cache time to live.
         */
        'ttl' => 86_400,

        /**
         * Cache prefix.
         */
        'cache_prefix' => 'frontcloner_',

        /**
         * Cache directory.
         */
        'cache_dir' => 'app/vendor/frontcloner/',

        /**
         * Use Laravel Cache-Facades instead of file-based cache.
         */
        'use_laravel_cache' => true,
    ],
];