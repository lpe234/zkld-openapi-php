<?php


namespace Zkld;

/**
 * User: lpe234
 * Date: 2019/12/13
 * Time: 10:30
 */
final class Auth {
    private $appId;
    private $appSecret;

    public function __construct($appId, $appSecret) {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
    }

    public function getAppId() {
        return $this->appId;
    }

    public function sign($data) {

        echo sprintf("\n\n%s\n\n", $this->appSecret . $data . $this->appSecret);

        return md5($this->appSecret . $data . $this->appSecret);
    }

    public function signRequest($method, $urlStr, $params, $body=null, $contentType=null) {
        $url = parse_url($urlStr);

        // write appSecret method
        $toSignStr = $method;

        // write request uri
        $path = $url['path'];
        $toSignStr .= $path;

        // write body
        if (!empty($body)) {
            $params['jsonBody'] = $body;
        }

        //
        ksort($params);

        $arrays = array();
        foreach ($params as $key => $val ) {
            $str = $key."=".$val;
            array_push($arrays, $str);
        }
        if (!empty($params)) {
            $toSignStr .= '?';
        }
        $toSignStr = $toSignStr.join("&", $arrays);

        // 生成签名
        $sign = $this->sign($toSignStr);
        return $sign;
    }
}