##supply-php-sdk

supply-php-sdk是北京胜天半子有限公司官方SDK的Composer封装，支持php项目的供应链API对接。

## 安装

* 通过composer，这是推荐的方式，可以使用composer.json 声明依赖，或者运行下面的命令。
```bash
$ composer require stbz-supply/php-sdk
```
* 直接下载安装，SDK 没有依赖其他第三方库，但需要参照 composer的autoloader，增加一个自己的autoloader程序。

## 运行环境

    php: >=7.0

## 使用方法

```php    
    use Stbz\Api\SupplyClient;
    //appkey、appSecret 在开放平台获取 https://open.jxhh.com
    $appKey = "your appkey"; 
    $appSecret = "your appSecret";
    
    try {
    	$supplyClient = new SupplyClient($appKey,$appSecret);
    } catch (OssException $e) {
    	printf(__FUNCTION__ . "creating supplyClient instance: FAILED\n");
    	printf($e->getMessage() . "\n");
    	return null;
    }
    
    //获取商品列表
    $param = ['page'=>1, 'limit'=>20, 'source'=>2];//请求参数
    $method = 'post';//请求方法
    $action = 'v2/Goods/Lists';//请求资源名
    $response = $supplyClient->getApiResponse($method,$action,$param);
```    

##供应链平台

开放平台地址 https://open.jxhh.com/  

选品平台地址 https://scm.jxhh.com/   



