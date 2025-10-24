<?php
namespace ThankSong\Lecang\Request;
use ThankSong\Lecang\Exceptions\InvalidParamsException;
use ThankSong\Lecang\Response\BasicResponse;

class GetAsnListRequest extends BasicRequest{

    /**
     * 页大小	最大200，不传默认200
     * @var int
     */
    public const MAX_PAGE_SIZE = 200;

    /**
     * 路径
     * @var string
     */
    public const END_POINT = '/oms/asnTemp/api/listByAsnCode';

    public const ASN_STATUS_ON_THE_WAY = 101201;
    public const ASN_STATUS_DELIVERED = 101204;
    public const ASN_STATUS_DELIVERING = 101206;
    public const ASN_STATUS_RECEIVING = 101209;
    public const ASN_STATUS_COMPLETED = 101210;
    public const ASN_STATUS_CANCELLED = 101210;
    public const ASN_STATUS_AFTER_SALE_CONFIRM = 101212;
    public const ASN_STATUS_AFTER_SALE_REJECT = 101213;

    public const ASN_TYPE_NORMAL = 101501;
    public const ASN_TYPE_RETURN = 101502;
    public const ASN_TYPE_EXCHANGE = 101503;

    public function __construct(array $params = []){
        $this -> setEndpoint(self::END_POINT)
              -> setMethod('POST');
        $this -> setPageNum(1)->setPageSize(self::MAX_PAGE_SIZE);
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

    public function setAsnNo(string $asn_no): static{
        $asn_nos = $this -> getParam('asnNoList') ?? [];
        if(!in_array($asn_no, $asn_nos)){
            $asn_nos[] = $asn_no;
        }
        $this -> setParam('asnNoList', $asn_nos);
        return $this;
    }

    public function setAsnNoList(array $asn_no_list): static{
        foreach ($asn_no_list as $asn_no){
            $this -> setAsnNo($asn_no);
        }
        return $this;
    }

    public function setErpNo(string $erp_no): static{
        $erp_nos = $this -> getParam('erpNoList') ?? [];
        if(!in_array($erp_no, $erp_nos)){
            $erp_nos[] = $erp_no;
        }
        $this -> setParam('erpNoList', $erp_nos);
        return $this;
    }

    public function setErpNoList(array $erp_no_list): static{
        foreach ($erp_no_list as $erp_no){
            $this -> setErpNo($erp_no);
        }
        return $this;
    }

    public function setAsnType(int $asn_type){
        $asn_types = [101501,101502,101503];
        if(!in_array($asn_type, $asn_types)){
            throw new InvalidParamsException('ASN类型不正确');
        }
        $this -> setParam('asnType', $asn_type);
        return $this;
    }

    /**
     * 设置发货单状态 101201-在途 ｜ 101204-已交接 ｜ 101205-已卸货 ｜ 101206-收货中 ｜ 101209-已完成 ｜ 101210-已取消 ｜ 101212-待售后确认 ｜ 101213-售后驳回
     * @param int $status
     * @throws \ThankSong\Lecang\Exceptions\InvalidParamsException
     * @return static
     */
    public function setStatus(int $status){
        $status_list = [101201,101204,101205,101206,101209,101210,101212,101213];
        if(!in_array($status, $status_list)){
            throw new InvalidParamsException('ASN状态不正确');
        }
        $this -> setParam('status', $status);
        return $this;
    }

    /**
     * 创建时间范围-开始时间
     * @param string $start_create_time
     * @return static
     */
    public function setStartCreateTime(string $start_create_time){
        $this -> setParam('createTimeStart', $start_create_time);
        return $this;
    }

    /**
     * 创建时间范围-结束时间
     * @param string $end_create_time
     * @return static
     */
    public function setEndCreateTime(string $end_create_time){
        $this -> setParam('createTimeEnd', $end_create_time);
        return $this;
    }

    /**
     * 更新时间范围-开始时间
     * @param string $start_update_time
     * @return static
     */
    public function setStartUpdateTime(string $start_update_time){
        $this -> setParam('updateTimeStart', $start_update_time);
        return $this;
    }

    /**
     * 更新时间范围-结束时间
     * @param string $end_update_time
     * @return static
     */
    public function setEndUpdateTime(string $end_update_time){
        $this -> setParam('updateTimeEnd', $end_update_time);
        return $this;
    }

    public function send():BasicResponse{
        $this -> validate();
        return BasicResponse::createFromArray($this -> doRequest());
    }

    protected function validate(){
        $params = $this -> getBody();
        if(empty($params['pageNum'])){
            $params['pageNum'] = 1;
        }
        if(empty($params['pageSize'])){
            $params['pageSize'] = 200;
        }
        if(!empty($params['status'])){
             $status_list = [101201,101204,101205,101206,101209,101210,101212,101213];
             if(!in_array($params['status'], $status_list)){
                $errors[] = 'ASN状态不正确';
             }
        }
        if(!empty($params['asnType'])){
            $asn_types = [101501,101502,101503];
            if(!in_array($params['asnType'], $asn_types)){
                $errors[] = 'ASN类型不正确';
            }
        }
        if(!empty($params['asnNoList']) && !empty($params['erpNoList'])){
            unset($params['erpNoList']);
        }
        $time_params = ['createTimeStart', 'createTimeEnd', 'updateTimeStart', 'updateTimeEnd'];
        foreach ($time_params as $time_param){
            if(!empty($params[$time_param])){
                if(!$this -> validateDateTime($params[$time_param])){
                    $errors[] = $time_param.'格式不正确';
                }
            }
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