<?php
namespace ThankSong\Lecang;
use ThankSong\Lecang\Request\BasicRequest;
use ThankSong\Lecang\Request\ExpenseTrialRequest;
use ThankSong\Lecang\Request\GetAsnListRequest;
use ThankSong\Lecang\Request\GetOrderListRequest;
use ThankSong\Lecang\Response\BasicResponse;
use ThankSong\Lecang\Request\GetProductListRequest;
use ThankSong\Lecang\Response\GetOrderListResponse;
use ThankSong\Lecang\Response\GetProductListResponse;
use ThankSong\Lecang\Request\GetInventoryListRequest;
use ThankSong\Lecang\Response\GetInventoryListResponse;

class Lecang {
    public static function basicReuqest(string $end_point, string $method, array $body = []): BasicResponse{
        $request = new BasicRequest();
        $request->setEndpoint($end_point)->setMethod($method);
        if (!empty($body)) {
            $request -> setBody($body);
        }
        return $request->send();
    }
    /**
     * 获取品类
     * @return BasicResponse
     */
    public static function getCategoryList(): BasicResponse{
        return self::basicReuqest('oms/category/api/list', 'GET');
    }

    /**
     * 获取仓库列表
     * @return BasicResponse
     */
    public static function getWarehouseList(): BasicResponse{
        return self::basicReuqest('oms/omsCustom/api/getWarehouse', 'GET');
    }

    /**
     * 获取承运商列表，可选仓库
     * @param string $warehouseCode
     * @return BasicResponse
     */
    public static function getCarrierList(string $warehouseCode = null): BasicResponse{
        $body = $warehouseCode? ['warehouseCode' => $warehouseCode] : [];
        return self::basicReuqest('tms/carrier/api/select', 'GET', $body);
    }

    /**
     * 获取快递产品列表，可选仓库
     * @param string $warehouseCode
     * @return BasicResponse
     */
    public static function getExpresssProductList(string $warehouseCode = null): BasicResponse{
        $body = $warehouseCode? ['warehouseCode' => $warehouseCode] : [];
        return self::basicReuqest('tms/saleProduct/api/list', 'GET', $body);
    }

    /**
     * 获取余额与信用额度
     * @return BasicResponse
     */
    public static function getBalance(): BasicResponse{
        return self::basicReuqest('oms/omsCustom/api/getBalance', 'GET');
    }

    /**
     * 获取商品列表
     * @param int $page_num
     * @param int $page_size
     * @return GetProductListResponse
     */
    public static function getProductList(int $page_num, int $page_size = 200): GetProductListResponse{
        $request = new GetProductListRequest();
        $request->setPageNum($page_num)->setPageSize($page_size);
        return $request->send();
    }

    /**
     * 获取库存列表
     * @param int $page_num
     * @param int $page_size
     * @return GetInventoryListResponse
     */
    public static function getInventoryList(int $page_num, int $page_size = 200): GetInventoryListResponse{
        $request = new GetInventoryListRequest();
        $request->setPageNum($page_num)->setPageSize($page_size);
        return $request->send();
    }

    /**
     * 获取订单列表 
     * @param array $params
     * @return GetOrderListResponse
     */
    public static function getOrderList(array $params = []): GetOrderListResponse{
        $request = new GetOrderListRequest($params);
        return $request->send();
    }

    /**
     * 批量查询发货通知单
     * @param array $params
     * @return BasicResponse
     */
    public static function getAsnList(array $params): BasicResponse{
        $request = new GetAsnListRequest($params);
        return $request->send();
    }

    /**
     * 费用试算
     * @param array $params
     * @return BasicResponse
     */
    public static function expenseTrial(array $params): BasicResponse{
        $request = new ExpenseTrialRequest($params);
        return $request->send();
    }

}