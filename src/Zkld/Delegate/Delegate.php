<?php

namespace Zkld\Delegate;

use Zkld\BaseService;

/**
 * 代表相关
 *
 * User: lpe234
 * Date: 2019/12/23
 * Time: 10:29
 */
class Delegate extends BaseService {

    /**
     * 添加代表信息
     *
     * @param $phone    string 手机号
     * @param $name     string 姓名
     * @param $role     string 代表级别; 30001-渠道机构, 30002-服务代表, 30003-区域代表
     * @return \Zkld\Http\Response
     */
    public function insertDelegateInfo($phone, $name, $role) {
        $uri = '/gateway/v1/delegate';

        $params['phone'] = $phone;
        $params['name'] = $name;
        $params['role'] = $role;

        $body = json_encode($params);
        $ret = $this->post($uri, $body);
        return $ret;
    }
}