<?php


namespace Zkld\Http;

/**
 * User: lpe234
 * Date: 2019/12/13
 * Time: 11:35
 */
class Request {

    public $url;
    public $params;
    public $headers;
    public $body;
    public $method;

    public function __construct($method, $url, array $params=array(), array $headers=array(), $body=null) {
        $this->method = strtoupper($method);
        $this->url = $url;
        $this->params = $params;
        $this->headers = $headers;
        $this->body = $body;
    }
}