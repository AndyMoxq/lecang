<?php
namespace ThankSong\Lecang\Request;
use ThankSong\Lecang\Response\GetProductListResponse;

class GetProductListRequest extends Client {
    public const END_POINT = 'oms/goods/api/list';
    public const MAX_PAGESIZE= 200;
    public function __construct(array $params = []){
        $this -> setEndpoint(self::END_POINT) -> setMethod('POST');
        $this -> setPageSize(self::MAX_PAGESIZE) -> setPageNum(1);
        if (!empty($params)) {
            $this -> setBody($params);
        }
    }

    /**
     * 设置页码
     * @param int $page_num
     * @return GetProductListRequest
     */
    public function setPageNum(int $page_num): static{
        $this->setParam('pageNum', $page_num);
        return $this;
    }

    /**
     * 设置每页数量
     * @param int $page_size
     * @return GetProductListRequest
     */
    public function setPageSize(int $page_size): static{
        if ($page_size > self::MAX_PAGESIZE) {
            $page_size = self::MAX_PAGESIZE;
        }
        $this->setParam('pageSize', $page_size);
        return $this;
    }

    /**
     * 获取响应结果
     * @return GetProductListResponse
     */
    public function send(): GetProductListResponse{
        $this -> validate();
        return GetProductListResponse::createFromArray($this->doRequest());
    }

    protected function validate(): void{
        if (! $this -> getParam('pageNum')) {
            throw new \Exception('pageNum is required');
        }
    }


}