<?php

namespace Openphp\MessageNotice\Channels;

use Openphp\MessageNotice\Channel;
use Openphp\MessageNotice\Http;

class Wechat extends Channel
{
    /**
     * @var string
     */
    protected $url = 'https://qyapi.weixin.qq.com/cgi-bin/webhook/send?key=';


    /**
     * @return mixed|string
     */
    public function send()
    {
        $timestamp = time();
        return Http::postJson($this->client, $this->getDomain(), [
            'timestamp' => $timestamp,
            'msgtype'   => 'text',
            'text'      => [
                'content'               => $this->getContent(),
                'mentioned_mobile_list' => $this->getAt(),
            ],
        ]);
    }

    /**
     * @return array|string[]
     */
    private function getAt()
    {
        $result = [];
        $at     = $this->content->getAt();
        if ((is_string($at) && $at == 'all') || (is_array($at) && in_array('all', $at))) {
            return ['@all'];
        }
        if (is_array($at)) {
            foreach ($at as $item) {
                $result[] = $item;
            }
        }
        return $result;
    }

    /**
     * @return string
     */
    private function getContent()
    {
        return $this->content->getContent();
    }

    /**
     * @return string
     */
    private function getDomain()
    {
        $token = $this->getConfig('token');
        return "{$this->url}{$token}";
    }
}