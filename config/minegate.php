<?php

return [
    // Internal address only, e.g. http://minegate-api:8080 or a Tailscale
    // hostname. Must be reachable directly from the panel server itself,
    // this should never be a publicly exposed URL.
    'api_host' => env('MINEGATE_API_HOST'),
];