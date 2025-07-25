<?php

namespace Vendor\FrontCloner;

use Error;
use Illuminate\Support\Facades\Cache;

class CloneService
{
    protected array $overrides = [];

    /**
     * Set CloneService configuration override.
     *
     * @param string $key Configuration key.
     * @param mixed $value Configuration value.
     * @return self
     */
    public function set(string $key, mixed $value): self
    {
        $this->overrides[$key] = $value;

        return $this;
    }

    /**
     * Check if the given URL exists in cache.
     *
     * @param string $url The URL to check.
     * @return bool
     */
    public function exists(string $url): bool
    {
        // Exists logic here
    }

    /**
     * Fetch the contents of a URL.
     *
     * @param string $url The URL to clone.
     * @param bool $check = false Check if host allowed.
     * @return string Document contents.
     */
    public function fetch(string $url, bool $check = false): string
    {
        // Looks for
    }

    /**
     * Clone content.
     *
     * @param string $url The URL of document to clone.
     * @param string $content The document contents.
     * @return void
     */
    public function put(string $url, string $content): void
    {
        // Put logic here
    }

    /**
     * Delete cloned content.
     *
     * @param string $url The URL of document to delete.
     * @return void
     */
    public function delete(string $url): void
    {
        // Delete logic here
    }

    // $cloner = \Vendor\FrontCloner\CloneService:set('allow_all', true);
    // $document = $cloner->fetch('https://url.com');
    // $cloner->put('https://url.com', $document);
    // $cloner->get();

    /**
     * Resolve configuration value with support for overrides and cache.
     *
     * @param string $key Configuration key.
     * @param mixed $default Default configuration value.
     * @return mixed
     */
    protected function getConfig(string $key, mixed $default = null): mixed
    {
        // Check for overridden value
        if(isset($this->overrides[$key]))
        {
            return $this->overrides[$key];
        }

        // Check cache
        $cacheKey = 'frontcloner.config.' . $key;
        if(Cache::has($cacheKey))
        {
            return Cache::get($cacheKey);
        }

        // Fall back to default config
        return config("frontcloner.{$key}", $default);
    }
}
