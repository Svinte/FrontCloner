<?php

namespace Svinte\FrontCloner\Services;

use Exception;
use FrontCloneCache;
use Illuminate\Support\Facades\Http;
use Svinte\FrontCloner\Helpers\UrlHelper;

class CloneService
{
    protected array $overrides = [];
    protected FrontCloneCache $cache;

    /**
     * Service construct.
     */
    public function __construct(FrontCloneCache $cache)
    {
        $this->cache = $cache;
    }

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
        $shortName = UrlHelper::normalize($url);

        return $this->cache->exists($shortName);
    }

    /**
     * Fetch the contents of a URL.
     *
     * @param string $url The URL to clone.
     * @param bool $check = false Check if host allowed.
     * @return string|null Document contents.
     */
    public function fetch(string $url, bool $check = false): string|null
    {
        $response = Http::get($url);
        $allowed = $check ? UrlHelper::isAllowedHost($url, $this->overrides) : true;

        if($allowed === false)
        {
            return null;
        }

        if($response->successful())
        {
            return $response->body();
        }

        throw new Exception("File fetch failed. Code {$response->status}");
    }

    /**
     * Clone content.
     *
     * @param string $url The URL of document to clone.
     * @param string $content The document contents.
     * @param int $ttl Cache time to live.
     * @return void
     */
    public function put(string $url, string $content, $ttl = null): void
    {
        $ttl = $ttl ?? $this->getConfig('cache.ttl');

        $shortName = UrlHelper::normalize($url);

        $this->cache->put($shortName, $content, $ttl);
    }

    /**
     * Delete cloned content.
     *
     * @param string $url The URL of document to delete.
     * @return void
     */
    public function delete(string $url): void
    {
        $shortName = UrlHelper::normalize($url);

        $this->cache->delete($shortName);
    }

    /**
     * Clear the whole cache.
     *
     * @return void
     */
    public function clear(): void
    {
        $this->cache->clear();
    }

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
        if($this->cache->exists($cacheKey))
        {
            return $this->cache->get($cacheKey);
        }

        // Fall back to default config
        return config("frontcloner.{$key}", $default);
    }
}
