<?php
namespace ThankSong\Lecang\Request;

use ThankSong\Lecang\Response\BasicResponse;

class ExpenseTrialRequest extends BasicRequest {
    /**
     * 路径
     * @var string
     */
    public const END_POINT = '/tms/expenseTrial/api/trial';

    /**
     * 请求方式
     * @var string
     */
    public const METHOD = 'POST';

    public function __construct(array $params=[]){
        $this -> setEndpoint(self::END_POINT)->setMethod(self::METHOD);
        if (!empty($params)) {
            $this -> setBody($params);
        }
    }

    /**
     * 设置仓库CODE
     * @param string $warehouse_code
     * @return static
     */
    public function setWarehouseCode(string $warehouse_code): static{
        $this -> setParam('warehouseCode', $warehouse_code);
        return $this;
    }

    /**
     * 设置产品名称
     * @param string $product_name
     * @return static
     */
    public function setProductName(string $product_name): static{
        $this -> setParam('productName', $product_name);
        return $this;
    }

    /**
     * 设置收件人地址信息
     * @param string $postal_code
     * @param string $country_code
     * @param string $province
     * @param string $city
     * @param string $address_line1
     * @param string $address_line2
     * @return static
     */
    public function setAddressInfo(string $postal_code,string $country_code,string $province,string $city,string $address_line1,string $address_line2 = null): static{
        $this -> setParam('postalCode',$postal_code)
              -> setParam('counryCode',$country_code)
              -> setParam('province', $province)
              -> setParam('city', $city)
              -> setParam('street1', $address_line1);
        if (!empty($address_line2)) {
            $this -> setParam('street2', $address_line2);
        }
        return $this;
    }


    /**
     * 设置产品尺寸信息
     * @param float $length
     * @param float $width
     * @param float $height
     * @param string $units
     * @return static
     */
    public function setProductSizeInfo(float $length, float $width, float $height, string $units = 'IN'){
        $this -> setParam('length', $length)
              -> setParam('width', $width)
              -> setParam('height', $height)
              -> setParam('lengthUnits',$units);
        return $this;
    }

    /**
     * 设置产品重量信息
     * @param float $weight
     * @param string $units
     * @return static
     */
    public function setProductWeightInfo(float $weight, string $units = 'LB'){
        $this -> setParam('weight', $weight)
              -> setParam('weightUnits', $units);
        return $this;
    }

    /**
     * 设置是否需要签名服务
     * @param string $signature_service
     * @return static
     */
    public function setSignatureService(string $signature_service){
        $this -> setParam('signatureService', $signature_service);
        return $this;
    }

    public function send(): BasicResponse{
        $this -> validate();
        return BasicResponse::createFromArray($this -> doRequest());
    }

    protected function validate(){
        $params = $this -> getBody();
        $required_params = ['warehouseCode', 'province', 'city','street1', 'postalCode', 'length', 'width', 'height', 'weight'];
        foreach ($required_params as $param) {
            if (empty($params[$param])) {
                $errors[] = "缺少必填参数{$param}";
            }
        }
        if (!empty($errors)) {
            throw new \Exception(implode(',', $errors));
        }
    }
}