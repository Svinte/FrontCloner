<?php

use Illuminate\Support\Facades\Cache;

class FrontCloneCache
{
    protected string $prefix;
    protected int $ttl;
    protected bool $useLaravelCache;
    protected string $cacheDir;

    /**
     * Construct cache class.
     */
    public function __construct()
    {
        $conf = config('frontcloner.cache');

        $this->prefix = $conf['cache_prefix'] ?? 'frontcloner_';
        $this->ttl = $conf['ttl'] ?? 86_400;
        $this->useLaravelCache = $conf['use_laravel_cache'] ?? true;
        $this->cacheDir = storage_path($conf['cache_dir'] ?? 'app/vendor/svinte/frontcloner/');

        if(is_dir($this->cacheDir) === false)
        {
            mkdir($this->cacheDir, 0755, true);
        }
    }


    /**
     * Check if file exists on cache.
     *
     * @param string $shortName File FrontCloner shortname.
     * @return bool Exists on cache.
     */
    public function exists(string $shortName): bool
    {
        # Laravel cache

        if($this->useLaravelCache)
        {
            return Cache::has($this->keyByName($shortName));
        }

        # File cache

        $file = $this->getCacheFilePath($shortName);

        if(file_exists($file) === false)
        {
            return false;
        }

        return (filemtime($file) + $this->ttl > time());   // Don't count expired
    }

    /**
     * Get cache file.
     *
     * @param string $shortName File FrontCloner shortname.
     * @return string|null
     */
    public function get(string $shortName): string|null
    {
        # Laravel cache

        if($this->useLaravelCache)
        {
            return Cache::get($this->keyByName($shortName));
        }

        # File cache

        if($this->exists($shortName) === false)
        {
            return null;
        }

        $file = $this->getCacheFilePath($shortName);
        $content = file_get_contents($file);

        return $content === false ? null : $content;
    }

    /**
     * Put file to cache.
     *
     * @param string $shortName File FrontCloner shortname.
     * @return void
     */
    public function put(string $shortName, $data)
    {
        # Laravel cache

        if($this->useLaravelCache)
        {
            Cache::put($this->keyByName($shortName), $data, now()->addSeconds($this->ttl));

            return;
        }

        # File cache

        $file = $this->getCacheFilePath($shortName);

        file_put_contents($file, $data);
    }

    /**
     * Delete cache file.
     *
     * @param string $shortName Cache file FrontCloner shortname.
     * @return void
     */
    public function delete(string $shortName): void
    {
        # Laravel cache

        if($this->useLaravelCache)
        {
            Cache::forget($this->keyByName($shortName));

            return;
        }

        # File cache

        $file = $this->getCacheFilePath($shortName);

        if(file_exists($file))
        {
            unlink($file);
        }
    }

    /**
     * Clear whole cache.
     *
     * @return void
     */
    public function clear(): void
    {
        # Laravel cache

        if($this->useLaravelCache)
        {
            Cache::flush();

            return;
        }

        # File cache

        $files = glob($this->cacheDir . '*.cache');

        foreach($files as $file)
        {
            unlink($file);
        }
    }


    /**
     * Get cache file's key using FrontCloner shortname.
     *
     * @param string $shortName
     * @return string File key.
     */
    protected function keyByName(string $shortName): string
    {
        return $this->prefix . $shortName;
    }

    /**
     * Get cache file's path.
     *
     * @param string $shortName
     */
    protected function getCacheFilePath(string $shortName): string
    {
        $safeName = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $shortName);

        return $this->cacheDir . $safeName . '.cache';
    }
}