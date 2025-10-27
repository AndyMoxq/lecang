<?php
namespace ThankSong\Lecang\Request;
use ThankSong\Lecang\Response\GetWaStorageFeeRespone;

class GetWaStorageFeeRequest extends BasicRequest {

    /**
     * 页大小	最大200，不传默认200
     * @var int
     */
    public const MAX_PAGE_SIZE = 200;

    /**
     * 路径
     * @var string
     */
    public const END_POINT = '/oms/warehouseDailyExpense/api/queryWarehouseDailyExpenseApiListByPage';

    public function __construct(array $params = []){
        $this -> setEndpoint(self::END_POINT)->setMethod('POST');
        $this -> setPageNum(1)->setPageSize(SELF::MAX_PAGE_SIZE);
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
     * 设置日结单号
     * @param string $daily_expense_no
     * @return static
     */
    public function setDailyExpenseNo(string $daily_expense_no){
        $this -> setParam('dailyExpenseNo', $daily_expense_no);
        return $this;
    }

    /**
     * 设置仓库
     * @param string $warehouse_code
     * @return static
     */
    public function setWarehouseCode(string $warehouse_code){
        $this -> setParam('warehouseCode', $warehouse_code);
        return $this;
    }

    /**
     * 设置计费开始时间 YYYYMMDD
     * @param string $billing_date_start
     * @return static
     */
    public function setBillingDateStart(string $billing_date_start){
        $this -> setParam('billingDateStart', $billing_date_start);
        return $this;
    }

    /**
     * 设置计费结束时间 YYYYMMDD
     * @param string $billing_date_end
     * @return static
     */
    public function setBillingDateEnd(string $billing_date_end){
        $this -> setParam('billingDateEnd', $billing_date_end);
        return $this;
    }

    protected function validate(){
        $errors = [];
        $params = $this->getBody();
        if (empty($params['pageNum'])) {
            $errors[] = 'pageNum is required';
        }
        if( !empty( $params['billingDateEnd'])){
            $billing_date_end = $params['billingDateEnd'];
            if(strlen($billing_date_end) != 8){
                $errors[] = 'billingDateEnd format error.Expected format: YYYYMMDD';
            }
        }
        if( !empty( $params['billingDateStart'])){
            $billing_date_start = $params['billingDateStart'];
            if(strlen($billing_date_start) != 8){
                $errors[] = 'billingDateStart format error.Expected format: YYYYMMDD';
            }
        }
        if (!empty($errors)) {
            throw new \InvalidArgumentException(implode(',', $errors));
        }
    }

    public function send(): GetWaStorageFeeRespone{
        $this->validate();
        return GetWaStorageFeeRespone::createFromArray($this->doRequest());
    }
    


}