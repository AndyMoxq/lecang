## 环境要求：
- PHP >= 7.1, 
- Laravel >= 5.5

## 安装
```bash
composer require thank-song/lecang
```

## 配置
配置 `.env` 文件,添加以下配置：
```
LECANG_ACCESS_KEY=your_access_key
LECANG_SECRET_KEY=your_secret_key
```
或发布配置文件到主项目：
```bash
php artisan vendor:publish --tag=lecang
```
然后在 `config/lecang.php` 文件中配置相关参数。

## 使用
### 使用方法一，使用工厂方法：
```php
use ThankSong\Lecang\Lecang;
$response = Lecang::getProductList(1, 10);
var_dump($response->getData());
var_dump($response->getTotal());
var_dump($response->hasMore());
```

### 使用方法二，实例化请求类：
```php
use ThankSong\Lecang\Request\GetProductListRequest;

$request = new GetProductListRequest();
$request -> setPageNum(1)->setPageSize(10);
$response = $request -> send();
var_dump($response->getData());
var_dump($response->getTotal());
var_dump($response->hasMore());
```

### 使用方法三，直接使用工厂类基本请求（应用于不支持的请求类）：
```php
use ThankSong\Lecang\Lecang;

$response = Lecang::basicRequest('oms/inventoryOverview/apiPage', ['pageNum' => 1, 'pageSize' => 10],'GET');
var_dump($response->getData());
var_dump($response->getTotal());
var_dump($response->hasMore());
```
更多受支持方法，请参考 `src/Request` 目录下的请求类。

## 注意事项
- 请确保您的服务器环境支持 `openssl` 扩展，否则无法使用签名验证。
- 请确保您的 `LECANG` 账户有相关权限，否则请求可能失败。

## 其他
- 欢迎提交 `PR` 或 `Issue`，共同完善此扩展。

## License
MIT License