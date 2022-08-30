<?php

namespace Openphp\MessageNotice;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;

class Http
{
    /**
     * @param Client $client
     * @param $uri
     * @param $json
     * @param $header
     * @return string
     */
    public static function postJson(Client $client, $uri, $json, $header = [])
    {
        try {
            $content = $client->request('POST', $uri, [
                RequestOptions::HEADERS => $header,
                RequestOptions::JSON    => $json,
            ]);
            return $content->getBody()->getContents();
        } catch (RequestException|GuzzleException $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * @param Client $client
     * @param $uri
     * @param $query
     * @param $header
     * @return string
     */
    public static function get(Client $client, $uri, $query, $header = [])
    {
        try {
            $content = $client->request('GET', $uri, [
                RequestOptions::QUERY   => $query,
                RequestOptions::HEADERS => $header,
            ]);
            return $content->getBody()->getContents();
        } catch (RequestException|GuzzleException $exception) {
            return $exception->getMessage();
        }
    }
}