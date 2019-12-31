<?php

namespace Zkld\MallOrder;

use Zkld\BaseService;

/**
 * 订单类
 *
 * User: lpe234
 * Date: 2019/12/31
 * Time: 16:56
 */
class MallOrder extends BaseService {

    /**
     * 查询所有订单
     *
     * @return \Zkld\Http\Response
     */
    public function getAllMallOrder() {
        $uri = '/gateway/v1/mallOrder';

        $ret = $this->get($uri);
        return $ret;
    }
}