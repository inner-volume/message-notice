<?php

namespace Openphp\MessageNotice;


use GuzzleHttp\Client;
use Openphp\MessageNotice\Channels\DingTalk;
use Openphp\MessageNotice\Channels\FeiShu;
use Openphp\MessageNotice\Channels\Wechat;

class MessageManager
{
    /**
     * @var string[]
     */
    protected $defaultChannel = [
        'dingtalk' => DingTalk::class,
        'wechat'   => Wechat::class,
        'feishu'   => FeiShu::class,
    ];

    /**
     * @var Channel[]
     */
    protected $channels = [];
    /**
     * @var Config
     */
    protected $config;
    /**
     * @var Content
     */
    protected $content;
    /**
     * @var Client
     */
    protected $client = null;

    /**
     * @param $config
     */
    public function __construct($config)
    {
        $this->config  = new Config($config);
        $this->content = new Content();
    }

    /**
     * @param Client $client
     * @return $this
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client ?: new Client();
    }


    /**
     * @param $channel
     * @return $this
     */
    public function channel($channel)
    {
        return $this->channels([$channel]);
    }

    /**
     * @param array $channels
     * @return $this
     */
    public function channels(array $channels)
    {
        foreach ($channels as $channel) {
            $channelClass = $this->defaultChannel[$channel] ?? $channel;
            if (!is_subclass_of($channelClass, Channel::class)) {
                throw new \InvalidArgumentException("Driver [$channel] not extend " . Channel::class);
            }
            $this->channels[$channel] = $channelClass;
        }
        return $this;
    }

    /**
     * @param $pipeline
     * @return $this
     */
    public function pipeline($pipeline)
    {
        $this->content->setPipeline($pipeline);
        return $this;
    }

    /**
     * @param string $at
     * @return $this
     */
    public function at($at = '')
    {
        if ($at) {
            $this->content->setAt($at);
        }
        return $this;
    }

    /**
     * @param $content
     * @return $this
     */
    public function content($content)
    {
        $this->content->setContent($content);
        return $this;
    }

    /**
     * @return void
     */
    public function send()
    {
        try {
            array_walk($this->channels, function (&$channel, $channelName) {
                /** @var Channel $channel */
                $channel = new $channel(
                    $this->config->get($channelName, []),
                    $this->content,
                    $this->getClient()
                );
                $channel->send();
            });
        } catch (\Throwable $e) {
            throw new MessageException($e->getMessage(), $e->getCode());
        }
    }
}