<?php
namespace ThankSong\Lecang\Request;
use ThankSong\Lecang\Exceptions\InvalidParamsException;
use ThankSong\Lecang\Response\GetInventoryListResponse;
class GetInventoryListRequest extends BasicRequest {

    /**
     * 页大小	最大200，不传默认200
     * @var int
     */
    public const MAX_PAGE_SIZE = 200;

    /**
     * 路径
     * @var string
     */
    public const END_POINT = 'oms/inventoryOverview/apiPage';

    public function __construct(array $params = []){
        $this -> setEndpoint(self::END_POINT)->setMethod('POST');
        if (!empty($params)) {
            $this -> setBody($params);
        }
    }

    /**
     * 设置页码
     * @param int $page_num
     * @return static
     */
    public function setPageNum(int $page_num){
        $this -> setParam('pageNum', $page_num);
        return $this;
    }

    /**
     * 设置页大小
     * @param int $page_size
     * @return static
     */
    public function setPageSize(int $page_size){
        $this ->setParam('pageSize', min($page_size, self::MAX_PAGE_SIZE));
        return $this;
    }

    /**
     * 设置货品编码
     * @param string $goods_code
     * @return static
     */
    public function setGoodsCode(string $goods_code): static{
        $this -> setParam('goodsCode', $goods_code);
        return $this;
    }

    /**
     * 设置货品等级 101301-良品 | 101302-等级品 | 101303-残次品
     * @param int $goods_level
     * @return void
     */
    public function setGoodsLevel(int $goods_level): static{
        if (!in_array($goods_level, [101301, 101302, 101303])) {
            throw new InvalidParamsException('Invalid goods level.Must be integer and in 101301, 101302 or 101303.');
        }
        $this -> setParam('goodsLevel', $goods_level);
        return $this;
    }

    /**
     * 设置有效期状态 101701-未临期 | 101702-临期 | 101703-过期 | null-无效期
     * @param int $valid_status
     * @return static
     */
    public function setValidStatus(int $valid_status): static{
        if (!in_array($valid_status, [101701, 101702, 101703, null])) {
            throw new InvalidParamsException('Invalid valid status.Must be integer and in 101701, 101702, 101703 or null.');
        }
        $this -> setParam('validStatus', $valid_status);
        return $this;
    }

    /**
     * 仓库编码
     * @param string $warehouse_code
     * @return static
     */
    public function setWarehouseCode(string $warehouse_code){
        $this -> setParam('warehouseCode', $warehouse_code);
        return $this;
    }

    /**
     * 设置库存起始值
     * @param int $goods_num_start
     * @return static
     */
    public function setGoodsNumStart(int $goods_num_start): static{
        $this -> setParam('goodsNumStart', $goods_num_start);
        return $this;
    }

    /**
     * 设置库存结束值
     * @param int $goods_num_end
     * @return static
     */
    public function setGoodsNumEnd(int $goods_num_end): static{
        $this -> setParam('goodsNumEnd', $goods_num_end);
        return $this;
    }

    /**
     * availableGoodsNumStart	库存可用起始值
     * @param int $available_goods_num_start
     * @return static
     */
    public function setAvailableGoodsNumStart(int $available_goods_num_start): static{
        $this -> setParam('availableGoodsNumStart', $available_goods_num_start);
        return $this;
    }

    /**
     * availableGoodsNumEnd	库存可用结束值
     * @param int $available_goods_num_end
     * @return static
     */
    public function setAvailableGoodsNumEnd(int $available_goods_num_end): static{
        $this -> setParam('availableGoodsNumEnd', $available_goods_num_end);
        return $this;
    }

    /**
     * 获取相应结果
     * @return GetInventoryListResponse
     */
    public function send(): GetInventoryListResponse{
        return GetInventoryListResponse::createFromArray($this->doRequest());
    }


}