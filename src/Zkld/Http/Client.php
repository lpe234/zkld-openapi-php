<?php


namespace Zkld\Http;

use Zkld\Config;

/**
 * User: lpe234
 * Date: 2019/12/13
 * Time: 11:22
 */
class Client {

    public static function get($url, array $params=array(), array $headers=array()) {
        $request = new Request('GET', $url, $params, $headers);
        return self::sendRequest($request);
    }

    public static function post($url, $body, array $params=array(), array $headers=array()) {
        $request = new Request('POST', $url, $params, $headers, $body);
        return self::sendRequest($request);
    }

    public static function put($url, $body, array $params=array(), array $headers=array()) {
        $request = new Request('PUT', $url, $params, $headers, $body);
        return self::sendRequest($request);
    }

    public static function delete($url, array $params=array(), array $headers=array()) {
        $request = new Request('DELETE', $url, $params, $headers);
        return self::sendRequest($request);
    }

    private static function userAgent() {
        $sdkInfo = "ZkldPHP/" . Config::SDK_VER;

        $systemInfo = php_uname('s');
        $machineInfo = php_uname('m');

        $envInfo = "($systemInfo/$machineInfo)";

        $phpVer = phpversion();

        $ua = "$sdkInfo $envInfo PHP/$phpVer";
        return $ua;
    }

    public static function sendRequest($request) {
        $t1 = microtime(true);
        $ch = curl_init();
        $options = array(
            CURLOPT_USERAGENT => self::userAgent(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HEADER => true,
            CURLOPT_NOBODY => false,
            CURLOPT_CUSTOMREQUEST => $request->method,
        );

        // set headers
        if (!empty($request->headers)) {
            $headers = array();
            foreach ($request->headers as $key => $val) {
                array_push($headers, "$key: $val");
            }
            $options[CURLOPT_HTTPHEADER] = $headers;
        }
        // set params
        $arrays = array();
        foreach ($request->params as $key => $val ) {
            $str = $key."=".urlencode($val);
            array_push($arrays, $str);
        }

        $options[CURLOPT_URL] = $request->url . '?' .join('&', $arrays);

        // set body
        if (!empty($request->body)) {
            $options[CURLOPT_POSTFIELDS] = $request->body;
        }
        // set options
        curl_setopt_array($ch, $options);

        // response
        $result = curl_exec($ch);
        $t2 = microtime(true);
        $duration = round($t2-$t1, 3);
        $ret = curl_errno($ch);
        if ($ret !== 0) {
            $r = new Response($duration, -1, curl_errno($ch), null);
            curl_close($ch);
            return $r;
        }

        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers = self::parseHeaders(substr($result, 0, $header_size));
        $body = substr($result, $header_size);
        // response
        $resultJson = json_decode($body, true);
        $code = $resultJson['code'];
        $msg = $resultJson['msg'];
        $data = array_key_exists('data', $resultJson)? $resultJson['data']: null;
        $traceId = array_key_exists('traceId', $resultJson)? $resultJson['traceId']: null;
        curl_close($ch);
        return new Response($duration, $code, $msg, $traceId, $headers, $data);
    }

    private static function parseHeaders($raw) {
        $headers = array();
        $headerLines = explode("\r\n", $raw);
        foreach ($headerLines as $line) {
            $headerLine = trim($line);
            $kv = explode(':', $headerLine);
            if (count($kv) > 1) {
                $kv[0] =self::ucwordsHyphen($kv[0]);
                $headers[$kv[0]] = trim($kv[1]);
            }
        }
        return $headers;
    }

    private static function ucwordsHyphen($str) {
        return str_replace('- ', '-', ucwords(str_replace('-', '- ', $str)));
    }
}

