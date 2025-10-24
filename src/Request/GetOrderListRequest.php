<?php
namespace ThankSong\Lecang\Request;
use ThankSong\Lecang\Exceptions\InvalidParamsException;
use ThankSong\Lecang\Response\GetOrderListResponse;

class GetOrderListRequest extends BasicRequest {

    /**
     * 页大小	最大200，不传默认200
     * @var int
     */
    public const MAX_PAGE_SIZE = 200;

    /**
     * 路径
     * @var string
     */
    public const END_POINT = '/oms/omsTocOrder/listByOrderNos';

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
    public function setPageSize(int $page_size): static{
        $this ->setParam('pageSize', min($page_size, self::MAX_PAGE_SIZE));
        return $this;
    }

    /**
     * 设置单个订单号
     * @param string $order_no
     * @return static
     */
    public function setOrderNo(string $order_no): static{
        $order_nos = $this -> getParam('orderNos') ?? [];
        if (!in_array($order_no, $order_nos)) {
          $order_nos[] = $order_no;
        }
        $this -> setParam('orderNos', $order_nos);
        return $this;
    }

    /**
     * 批量设置订单号
     * @param array $order_nos
     * @return static
     */
    public function setOrderNos(array $order_nos): static{
        foreach ($order_nos as $order_no) {
            $this -> setOrderNo($order_no);
        }
        return $this;
    }

    /**
     * 设置参考号
     * @param string $reference_no
     * @return static
     */
    public function setReferenceNo(string $reference_no){
        $reference_nos = $this -> getParam('referenceNos') ?? [];
        if (!in_array($reference_no, $reference_nos)) {
            $reference_nos[] = $reference_no;
        }
        $this -> setParam('referenceNos', $reference_nos);
        return $this;

    }

    /**
     * 批量设置参考号
     * @param array $reference_nos
     * @return static
     */
    public function setReferenceNos(array $reference_nos){
        foreach ($reference_nos as $reference_no) {
            $this -> setReferenceNo($reference_no);
        }
        return $this;
    }

    /**
     * 创建时间范围-开始时间
     * @param string $start_create_time
     * @return static
     */
    public function setStartCreateTime(string $start_create_time){
        $this -> setParam('startCreateTime', $start_create_time);
        return $this;
    }

    /**
     * 创建时间范围-结束时间
     * @param string $end_create_time
     * @return static
     */
    public function setEndCreateTime(string $end_create_time){
        $this -> setParam('endCreateTime', $end_create_time);
        return $this;
    }

    /**
     * 更新时间范围-开始时间
     * @param string $start_update_time
     * @return static
     */
    public function setStartUpdateTime(string $start_update_time){
        $this -> setParam('startUpdateTime', $start_update_time);
        return $this;
    }

    /**
     * 更新时间范围-结束时间
     * @param string $end_update_time
     * @return static
     */
    public function setEndUpdateTime(string $end_update_time){
        $this -> setParam('endUpdateTime', $end_update_time);
        return $this;
    }

    public function send(): GetOrderListResponse{
        $this -> validate();
        return GetOrderListResponse::createFromArray($this -> doRequest());
    }



    protected function validate(){
        $params = $this -> getBody();
        if(empty($params['pageNum'])){
            $errors[] = 'pageNum不能为空';
        }
        if(!empty($params['orderNos']) && !empty($params['referenceNos'])){
            unset($params['referenceNos']);
        }
        if(!empty($params['startCreateTime']) && !$this -> validateDateTime($params['startCreateTime'])){
            $errors[] = 'startCreateTime日期格式不正确';
        }
        if(!empty($params['startCreateTime']) && !$this -> validateDateTime($params['startCreateTime'])){
            $errors[] = 'endCreateTime日期格式不正确';
        }
        if(!empty($params['startUpdateTime']) && !$this -> validateDateTime($params['startUpdateTime'])){
            $errors[] = 'startUpdateTime日期格式不正确';
        }
        if(!empty($params['endUpdateTime']) && !$this -> validateDateTime($params['endUpdateTime'])){
            $errors[] = 'endUpdateTime日期格式不正确';
        }
        if(!empty($errors)){
            throw new InvalidParamsException(implode(',', $errors));
        }
        $this -> setBody($params);

    }

    private function validateDateTime($date_time): bool{
        if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $date_time)) {
            return true;
        }
        return false;
    }





    
}