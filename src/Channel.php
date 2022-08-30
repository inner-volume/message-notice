<?php

namespace Openphp\MessageNotice;

use GuzzleHttp\Client;

abstract class Channel
{
    /**
     * @var []
     */
    protected $config = [];
    /**
     * @var Content
     */
    protected $content;
    /**
     * @var Client|null
     */
    protected $client;

    /**
     * @param array $config
     * @param Content $content
     * @param Client|null $client
     */
    public function __construct(array $config, Content $content, Client $client)
    {
        $this->config  = $config;
        $this->client  = $client;
        $this->content = $content;
    }

    /**
     * @param string $key
     * @return array|mixed
     */
    public function getConfig(string $key = '')
    {
        if (isset($this->config['pipeline'][$this->content->getPipeline()][$key])) {
            return $this->config['pipeline'][$this->content->getPipeline()][$key];
        }
        if (empty($key)) {
            return $this->config;
        }
        return $this->config[$key] ?? $this->config;
    }

    /**
     * @return mixed
     */
    abstract public function send();
}