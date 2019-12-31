<?php


namespace Zkld;

use Zkld\Http\Client;

/**
 * User: lpe234
 * Date: 2019/12/21
 * Time: 09:53
 */
class BaseService {

    private $auth;
    private $config;

    public function __construct(Auth $auth, Config $config=null) {
        $this->auth = $auth;
        if ($config == null) {
            $this->config = new Config();
        } else {
            $this->config = $config;
        }
    }

    public function getAuth() {
        return $this->auth;
    }

    public function getConfig() {
        return $this->config;
    }

    protected function get($uri, $params=array()) {
        $params['appId'] = $this->auth->getAppId();
        $params['timestamp'] = date('c');
        $params['nonce'] = $this->genRndStrs(16);
        $sign = $this->auth->signRequest('GET', $uri, $params);
        $params['signature'] = $sign;

        echo sprintf("\n\n%s\n\n", $sign);

        $url = $this->config->getApiHost().$uri;

        $ret = Client::get($url, $params);
        return $ret;
    }

    protected function post($uri, $body=null, $params=array(), $contentType='application/json') {
        $params['appId'] = $this->auth->getAppId();
        $params['timestamp'] = date('c');
        $params['nonce'] = $this->genRndStrs(16);
        $sign = $this->auth->signRequest('POST', $uri, $params, $body);
        $params['signature'] = $sign;

        echo sprintf("\n\n%s\n\n", $sign);

        $url = $this->config->getApiHost().$uri;

        $headers['Content-Type'] = $contentType;
        $ret = Client::post($url, $body, $params, $headers);
        return $ret;
    }

    private function genRndStrs($len) {
        $strs = "QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm";
        return substr(str_shuffle($strs),mt_rand(0,strlen($strs)-11),$len);
    }
}
