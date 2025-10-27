<?php
namespace ThankSong\Lecang\Request;

use ThankSong\Lecang\Response\BasicResponse;

class GetWaStorageFeeDetailsRequest extends BasicRequest {
    public const END_POINT = '/oms/warehouseDailyExpense/api/queryWarehouseDailyExpenseDetailApiListByPage';

    public function __construct(int $id = null){
        $this -> setMethod('POST');
        $this -> setEndpoint(SELF::END_POINT);
        if($id!== null) $this -> setDailyExpenseId($id);
    }

    public function setDailyExpenseId(int $id): static{
        $this -> setParam('dailyExpenseId', $id);
        return $this;
    }

    public function validate(){
        $dailyExpenseId = $this -> getParam('dailyExpenseId');
        if(!is_numeric($dailyExpenseId) || $dailyExpenseId < 1){
            throw new \InvalidArgumentException('dailyExpenseId is required and must be a positive integer');
        }
    }

    public function send(): BasicResponse{
        $this->validate();
        return BasicResponse::createFromArray($this->doRequest());
    }

}