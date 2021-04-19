<?php

namespace Tests\Traits;

/**
 * Trait RequestTrait
 */
trait RequestTrait
{
    /**
     * @param $uri
     * @param array $query
     * @param array $headers
     * @return \Illuminate\Testing\TestResponse
     */
    public function get($uri, array $query = [], array $headers = [])
    {
        $server = $this->transformHeadersToServerVars($headers);
        $cookies = $this->prepareCookiesForRequest();

        return $this->call('GET', $uri, $query, $cookies, [], $server);
    }

    /**
     * Visit the given URI with a GET request, expecting a JSON response.
     *
     * @param string $uri
     * @param array $query
     * @param array $headers
     * @return \Illuminate\Testing\TestResponse
     */
    public function getJson($uri, array $query = [], array $headers = [])
    {
        return $this->json('GET', $uri, $query, $headers);
    }
}
