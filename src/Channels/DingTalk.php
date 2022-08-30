<?php

namespace Openphp\MessageNotice\Channels;

use Openphp\MessageNotice\Channel;
use Openphp\MessageNotice\Http;
use Openphp\MessageNotice\MessageException;

class DingTalk extends Channel
{
    /**
     * @var string
     */
    protected $url = 'https://oapi.dingtalk.com/robot/send';

    /**
     * @return mixed|string
     */
    public function send()
    {
        $resp = Http::postJson($this->client, $this->getDomain(), [
            'msgtype' => 'text',
            'text'    => ['content' => $this->content->getContent()],
            'at'      => $this->getAt(),
        ]);
        $data = json_decode($resp, true);
        if ($data['errcode']) {
            throw new MessageException($data['errmsg']);
        }
        return $data;
    }

    /**
     * 生成请求地址
     */
    private function getDomain(): string
    {
        $time   = time() * 1000;
        $token  = $this->getConfig('token');
        $secret = $this->getConfig('secret');
        $secret = hash_hmac('sha256', $time . "\n" . $secret, $secret, true);
        $sign   = urlencode(base64_encode($secret));
        return "{$this->url}?access_token={$token}&timestamp={$time}&sign={$sign}";
    }

    /**
     * 生成@.
     * @return array|array[]|bool[]
     */
    private function getAt(): array
    {
        $result = [];
        $at     = $this->content->getAt();
        if ((is_string($at) && $at == 'all') || (is_array($at) && in_array('all', $at))) {
            return ['isAtAll' => true,];
        }
        if (is_array($at)) {
            $result = ['atMobiles' => $at,];
        }
        return $result;
    }
}