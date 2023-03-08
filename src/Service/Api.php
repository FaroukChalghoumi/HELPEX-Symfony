<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;


class Api
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }


    public function getdata(): array
    {
        $response = $this->client->request(
            'GET',
            'https://api.covidtracking.com/v1/us/current.json'
        );

        return $response->toArray();
    }







}