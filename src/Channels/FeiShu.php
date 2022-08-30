<?php

namespace Openphp\MessageNotice\Channels;

use Openphp\MessageNotice\Channel;
use Openphp\MessageNotice\Http;
use Openphp\MessageNotice\MessageException;

class FeiShu extends Channel
{
    /**
     * @var string
     */
    protected $url = 'https://open.feishu.cn/open-apis/bot/v2/hook/';

    /**
     * @return mixed|string
     */
    public function send()
    {
        $timestamp = time();
        $resp      = Http::postJson($this->client, $this->getDomain(), [
            'timestamp' => $timestamp,
            'sign'      => $this->getSign($timestamp),
            'msg_type'  => 'text',
            'content'   => [
                'text' => $this->getContent(),
            ],
        ]);
        $data      = json_decode($resp, true);
        if (isset($data['code']) && isset($data['msg'])) {
            throw new MessageException($data['msg']);
        }
        return $data;
    }

    /**
     * 生成签名.
     * @param int $timestamp 时间戳
     */
    private function getSign($timestamp)
    {
        $secret = $this->getConfig('secret');
        $secret = hash_hmac('sha256', '', $timestamp . "\n" . $secret, true);
        return base64_encode($secret);
    }

    /**
     * 生成请求地址
     */
    private function getDomain()
    {
        $token = $this->getConfig('token');
        return "{$this->url}/{$token}";
    }

    /**
     * 生成内容.
     */
    private function getContent()
    {
        return $this->content->getContent() . $this->getAt();
    }

    /**
     * 生成@.
     */
    private function getAt()
    {
        $result = '';
        $at     = $this->content->getAt();
        if ((is_string($at) && $at == 'all') || (is_array($at) && in_array('all', $at))) {
            return '<at user_id="all">所有人</at>';
        }
        if (is_array($at)) {
            foreach ($at as $item) {
                if (strchr($item, '@') === false) {
                    $result .= '<at phone="' . $item . '">' . $item . '</at>';
                } else {
                    $result .= '<at mail="' . $item . '">' . $item . '</at>';
                }
            }
        }
        return $result;
    }
}