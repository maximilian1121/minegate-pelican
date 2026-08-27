<?php

namespace Maximilian1121\Minegate\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use WebSocket\Client;
use WebSocket\Exception\Exception as WebSocketException;

class MinegateClient
{
    /**
     * Get the base API host from config.
     */
    protected function getBaseUrl(): ?string
    {
        $host = config('minegate.api_host');
        return empty($host) ? null : rtrim($host, '/');
    }

    /**
     * Fetch the WebSocket status.
     *
     * @return array<string, mixed>|null
     */
    public function fetchStatus(): ?array
    {
        $baseUrl = $this->getBaseUrl();
        if (!$baseUrl)
            return null;

        try {
            $response = Http::get("{$baseUrl}/health");
            return $response->successful() ? $response->json() : null;
        } catch (Exception $exception) {
            report($exception);
            return null;
        }
    }

    /**
     * Get a specific route by subdomain.
     *
     * @param string $subdomain
     * @return array<string, mixed>|null
     */
    public function getRoute(string $subdomain): ?array
    {
        $baseUrl = $this->getBaseUrl();
        if (!$baseUrl)
            return null;

        try {
            $response = Http::get("{$baseUrl}/route/{$subdomain}");
            return $response->successful() ? $response->json() : null;
        } catch (Exception $exception) {
            report($exception);
            return null;
        }
    }

    /**
     * Create a new route.
     *
     * @param string $subdomain
     * @param string $host
     * @param int $port
     * @return array<string, mixed>|null
     */
    public function createRoute(string $subdomain, string $host, int $port = 25565): ?array
    {
        $baseUrl = $this->getBaseUrl();
        if (!$baseUrl)
            return null;

        try {
            $response = Http::post("{$baseUrl}/route", [
                'subdomain' => $subdomain,
                'host' => $host,
                'port' => $port,
            ]);
            return $response->successful() ? $response->json() : null;
        } catch (Exception $exception) {
            report($exception);
            return null;
        }
    }

    /**
     * Update an existing route.
     *
     * @param string $subdomain
     * @param string|null $host
     * @param int|null $port
     * @return array<string, mixed>|null
     */
    public function updateRoute(string $subdomain, ?string $host = null, ?int $port = null): ?array
    {
        $baseUrl = $this->getBaseUrl();
        if (!$baseUrl)
            return null;

        $payload = [];
        if ($host !== null) {
            $payload['host'] = $host;
        }
        if ($port !== null) {
            $payload['port'] = $port;
        }

        try {
            $response = Http::put("{$baseUrl}/route/{$subdomain}", $payload);
            return $response->successful() ? $response->json() : null;
        } catch (Exception $exception) {
            report($exception);
            return null;
        }
    }

    /**
     * Delete a route by subdomain.
     *
     * @param string $subdomain
     * @return bool
     */
    public function deleteRoute(string $subdomain): bool
    {
        $baseUrl = $this->getBaseUrl();
        if (!$baseUrl)
            return false;

        try {
            $response = Http::delete("{$baseUrl}/route/{$subdomain}");
            return $response->successful();
        } catch (Exception $exception) {
            report($exception);
            return false;
        }
    }

    /**
     * List all routes.
     *
     * @return array<string, mixed>|null
     */
    public function listRoutes(): ?array
    {
        $baseUrl = $this->getBaseUrl();
        if (!$baseUrl)
            return null;

        try {
            $response = Http::get("{$baseUrl}/routes");
            return $response->successful() ? $response->json() : null;
        } catch (Exception $exception) {
            report($exception);
            return null;
        }
    }

    /**
     * Check API health.
     *
     * @return array<string, mixed>|null
     */
    public function checkHealth(): ?array
    {
        $baseUrl = $this->getBaseUrl();
        if (!$baseUrl)
            return null;

        try {
            $response = Http::get("{$baseUrl}/health");
            return $response->successful() ? $response->json() : null;
        } catch (Exception $exception) {
            report($exception);
            return null;
        }
    }
}