<?php

namespace Svinte\FrontCloner\Helpers;

class UrlHelper
{
    /**
     * Normalized version of URL. Used for cloning path identification.
     * Removes prototype, default ports and www-subdomains.
     *
     * @param string $url
     * @return string Normalized URL.
     */
    public static function normalize(string $url): string
    {
        $url = trim($url);

        // Schema required for parse_url
        if(preg_match('/^https?:\/\//i', $url) === false)
        {
            $url = 'http://' . $url;
        }

        $parts = parse_url($url);

        $host = $parts['host'] ?? '';
        $port = $parts['port'] ?? null;

        // Remove www-subdomain
        $host = preg_replace('/^www\./', '', $host);

        // Remove default HTTP & HTTPS ports
        $usePort = ($port && in_array($port, [80, 443]) === false) ? ':' . $port : '';

        $path = rtrim($parts['path'] ?? '', '/');

        return strtolower($host . $usePort . $path);
    }

    /**
     * Check if URL is allowed.
     * Looks for configuration values allow_all, blacklist and whitelist.
     *
     * @param string $url The URL to look for.
     * @param array $overrides Overrides configuration.
     * @return bool Is allowed.
     */
    public static function isAllowedHost(string $url, array $overrides = []): bool
    {
        $config = array_merge(config('frontcloner.hosts'), $overrides);
        $normalizedUrl = self::normalize($url);

        // All hosts allowed.
        if($config['allow_all'] ?? false)
        {
            return true;
        }

        // Blacklisted.
        foreach($config['blacklist'] ?? [] as $pattern)
        {
            $regex = self::globToRegex($pattern);
            if(preg_match("#{$regex}#", $normalizedUrl))
            {
                return false;
            }
        }

        // Whitelisted.
        foreach($config['whitelist'] ?? [] as $pattern)
        {
            $regex = self::globToRegex($pattern);
            if(preg_match("#{$regex}#", $normalizedUrl))
            {
                return true;
            }
        }

        return false;
    }

    /**
     * Convert glob pattern to regex.
     *
     * @param string $glob
     * @return string
     */
    public static function globToRegex(string $glob): string
    {
        $escaped = preg_quote($glob, '#');

        $pattern = str_replace(['\*', '\?'], ['.*', '.'], $escaped);

        return '#^' . $pattern . '$#i';
    }
}