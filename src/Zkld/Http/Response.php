<?php


namespace Zkld\Http;

/**
 * User: lpe234
 * Date: 2019/12/13
 * Time: 11:45
 */
class Response {

    public $code;
    public $msg;
    public $data;
    public $tradeId;
    public $headers;

    public $duration;

    public function __construct($duration, $code, $msg, $tradeId, array $headers = array(), $data = null) {
        $this->duration = $duration;
        $this->code = $code;
        $this->msg = $msg;
        $this->tradeId = $tradeId;
        $this->headers = $headers;
        $this->data = $data;

        if ($code !== 200) {
            return;
        }

        if ($data === null) {
            return;
        }

        return;
    }

    public function traceId() {
        return $this->tradeId;
    }

    public function ok() {
        return $this->code === 200;
    }
}
