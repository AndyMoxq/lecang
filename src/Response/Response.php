<?php
namespace ThankSong\Lecang\Response;
use ThankSong\Lecang\Exceptions\InvalidResponseException;

abstract class Response {
    /**
     * @var array
     */
    protected $body = [];

    /**
     * @var int
     */
    protected $code = 0;

    /**
     * @var string
     */
    protected $message = '';
    public function __construct(array $response){
        $this -> body = $response;
        if (isset($response['code']) && (isset($response['message']) || isset($response['msg']))) {
            $message = $response['message'] ?? ($response['msg'] ?? 'Unknown error');
            $this -> setMessage($message);
            $this -> setCode($response['code'] ?? 0);
            if($this -> getCode() != 200){
                throw new InvalidResponseException("[{$this -> getCode()}] " . $this -> getMessage());
            }
        }else{
            $message = $response['message'] ?? ($response['msg'] ?? 'Unknown error');
            throw new InvalidResponseException("Invalid response format: $message");
        }
    }

    public static function createFromArray(array $response): static{
        return new static($response);
    }

    /**
     * 获取响应体
     * @return array
     */
    public function getBody() : array {
        return $this -> body;
    }

    /**
     * 获取响应码
     * @return int
     */
    public function getCode(): int {
        return $this -> code;
    }

    /**
     * 设置响应码
     * @param int $code
     * @return static
     */
    public function setCode(int $code): static {
        $this -> code = $code;
        return $this;
    }

    /**
     * 获取响应信息
     * @return string
     */
    public function getMessage(): string {
        return $this -> message;
    }

    /**
     * 设置响应信息
     * @param $message
     * @return static
     */
    public function setMessage($message): static {
        $this -> message = $message;
        return $this;
    }

    /**
     * 获取数据
     * @return mixed|null
     */
    public function getData(): mixed {
        return $this -> getBody()['data'] ?? null;
    }

    abstract public function validate();


}