<?php

namespace App\Api;

use GuzzleHttp\Client;

class ApiClient
{
    protected Client $client;

    protected $routes = [
        'start'       => 'start/',
        'status'      => 'status/{gameId}/{playerCode}',
        'clear-field' => 'clear-field/{gameId}/{playerCode}',
        'place-ship'  => 'place-ship/{gameId}/{playerCode}',
        'ready'       => 'ready/{gameId}/{playerCode}',
        'shot'        => 'shot/{gameId}/{playerCode}',
    ];

    public function __construct(
        protected $apiUrl = '',
        protected $gameId = 0,
        protected $playerCode = ''
    ) {
        $this->client = new Client([
            'base_uri' => $this->apiUrl . '/api/',
            'timeout' => 5.0,
        ]);
    }
    public function apiRequest(string $apiMethod, $data = []): mixed
    {
        $url = $this->routes[$apiMethod] ?? $apiMethod;
        $url = str_replace('{gameId}', $this->gameId, $url);
        $url = str_replace('{playerCode}', $this->playerCode, $url);

        $result = $this->client->request('POST', $url, $data ? ['form_params' => $data] : [])->getBody()->getContents();
        $result = json_decode($result);

        if ($apiMethod === 'start' && isset($result->id) && isset($result->code)) {
            $this->gameId = $result->id;
            $this->playerCode = $result->code;
        }

        return $result;
    }
}
