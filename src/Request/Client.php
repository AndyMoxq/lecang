<?php

namespace ThankSong\Lecang\Request;
use ThankSong\Lecang\Tools\Sign;
use Illuminate\Support\Facades\Http;
use ThankSong\Lecang\Exceptions\InvalidResponseException;
use ThankSong\Lecang\Exceptions\InvalidParamsException;

abstract class Client 
{
    /**
     * 密钥
     * @var string
     */
    private $access_key;

    /**
     * 私钥
     * @var string
     */
    private $secret_key;

    /**
     * 是否开发环境
     * @var bool
     */
    private $is_dev = false;

    /**
     * API地址
     * @var string
     */
    private $api_url;

    /**
     * 请求路径
     * @var string
     */
    private $end_point;

    /**
     * 请求方法
     * @var string
     */
    private $method = 'POST';

    /**
     * 请求头
     * @var array
     */
    private $headers = [];

    /**
     * 请求体
     * @var array
     */
    private $body = [];

    /**
     * 设置密钥
     * @param string $access_key
     * @return  static
     */
    public function setAccessKey(string $access_key): static{
        $this->access_key = $access_key;
        return $this;
    }

    /**
     * 获取密钥
     * @return string
     */
    protected function getAccessKey(): string{
        return $this->access_key ?: config('lecang.access_key','your_access_key');
    }

    /**
     * 设置私钥
     * @param string $secret_key
     * @return static
     */
    public function setSecretKey(string $secret_key): static{
        $this -> secret_key = $secret_key;
        return $this;
    }

    /**
     * 获取私钥
     * @return string
     */
    protected function getSecretKey(): string{
        return $this->secret_key ?: config('lecang.secret_key','your_secret_key');
    }

    /**
     * 设置API地址
     * @param string $api_url
     * @return static
     */
    public function setApiUrl(string $api_url): static{
        $this->api_url = $api_url;
        return $this;
    }

    /**
     * 设置是否开发环境
     * @param bool $is_dev
     * @return static
     */
    public function setIsDev(bool $is_dev): static{
        $this->is_dev = $is_dev;
        return $this;
    }
    /**
     * 获取API地址
     * @return string
     */
    protected function getApiUrl(): string{
        return $this->api_url ?: config('lecang.api_url','https://app.lecangs.com/api');
    }

    /**
     * 设置请求头
     * @param array $headers
     * @return  static
     */
    public function setHeaders(array $headers): static{
        $this->headers = $headers;
        return $this;
    }

    /**
     * 获取请求头
     * @return array
     */
    protected function getHeaders(): array{
        return $this->headers;
    }

    /**
     * 设置请求体
     * @param array $body
     * @return  static
     */
    public function setBody(array $body): static{
        $this->body = $body;
        return $this;
    }

    /**
     * 获取请求体
     * @return array
     */
    protected function getBody(): array{
        return $this->body;
    }

    /**
     * 设置请求参数
     * @param string $key
     * @param mixed $value
     * @return  static
     */
    public function setParam(string $key, $value): static{
        $this->body[$key] = $value;
        return $this;
    }

    /**
     * 获取请求参数
     * @param string $key
     * @return mixed|null
     */
    public function getParam(string $key){
        return $this->body[$key]?? null;
    }

    /**
     * 设置请求路径
     * @param string $end_point
     * @return  static
     */
    public function setEndpoint(string $end_point): static{
        $this -> end_point = $end_point;
        return $this;
    }

    /**
     * 获取请求路径
     * @return string
     */
    protected function getEndpoint(): string{
        return trim($this->end_point, '/') ;
    }

    /**
     * 设置请求方法
     * @param string $method
     * @return  static
     */
    public function setMethod(string $method): static{
        $method = strtoupper($method);
        if(!in_array($method, ['GET', 'POST'])){
             throw new InvalidParamsException('Invalid request method.Must Be in [GET, POST]');
        }
        $this->method = $method;
        return $this;
    }

    /**
     * 获取请求方法
     * @return string
     */
    protected function getMethod(): string{
        return $this->method;
    }

    /**
     * 拼接请求地址
     * @return string
     */
    private function getFullUrl(): string{
        $end_point = $this->getEndpoint();
        return $this -> is_dev ? "https://apprelease.lecangs.com/api/{$this->getEndpoint()}": ($this->getApiUrl() . '/' . $end_point);
    }

    /**
     * 发送请求
     * @throws \ThankSong\Lecang\Exceptions\InvalidResponseException
     */
    protected function doRequest(){
        $access_key = $this->getAccessKey() ?: config('lecang.access_key');
        $secret_key = $this->getSecretKey() ?: config('lecang.secret_key');
        $url = $this->getFullUrl();
        $timestamp = time() * 1000;
        $body = $this -> getMethod() == 'GET' ? [] : $this->getBody();
        $sign = Sign::make($timestamp, $access_key, $secret_key, $body);
        $headers = array_merge($this->getHeaders(), [
            'accessKey' => $access_key,
            'timestamp' => $timestamp,
            'sign' => $sign,
        ]);
        if($this->getMethod() == 'GET'){
            $url .= '?'. http_build_query($this->getBody());
            $response = Http::withHeaders($headers)->get($url, $this->getBody());
        }

        if($this->getMethod() == 'POST'){
            $response = Http::withHeaders($headers)->post($url, $this -> getBody());
        }

        if($response -> status() != 200){
            throw new InvalidResponseException($response -> body());
        }
        return $response -> json();
    }

    abstract public function send();

    abstract protected function validate();



    
}