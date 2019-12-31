<?php


namespace Zkld;

/**
 * User: lpe234
 * Date: 2019/12/13
 * Time: 10:23
 */
final class Config {
    const SDK_VER = "1.0.0";

    const API_HOST = "api.weoathome.com";

    const VIRTUAL_DIR = "/gateway";

    public $useHTTPS;

    public function __construct() {
        $this->useHTTPS = false;
    }

    public function getApiHost() {
        if ($this->useHTTPS === true) {
            $scheme = 'https://';
        } else {
            $scheme = 'http://';
        }

        return $scheme . self::API_HOST;
    }
}