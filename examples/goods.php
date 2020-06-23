<?php
/**
 * Created by PhpStorm.
 * User: stbz
 * Date: 2020/6/17
 * Time: 2:04 PM
 */


require_once __DIR__ . '/../autoload.php';

use Stbz\Api\SupplyClient;

$appKey = "123stbz456";
$appSecret = "WywhCb6iYyshGBO0caWn3GLSeKloOnsn";

try {
	$supplyClient = new SupplyClient($appKey,$appSecret);
} catch (OssException $e) {
	printf(__FUNCTION__ . "creating supplyClient instance: FAILED\n");
	printf($e->getMessage() . "\n");
	return null;
}

//获取商品列表
$param = ['page'=>1, 'limit'=>20, 'source'=>2];
$response = $supplyClient->getApiResponse("get","/v2/Goods/Lists",$param);

//获取商品详情
//$param = ['id'=>890394];
//$response = $supplyClient->getApiResponse("get","/v2/Goods/Detail",$param);

//批量获取详情
//$param = ['ids'=>'890394,890395'];
//$response = $supplyClient->getApiResponse("get","/v2/Goods/GetBulkGoodDetail",$param);

//获取全量分类
//$param = ['page'=>1, 'limit'=>20, 'source'=>2];
//$response = $supplyClient->getApiResponse("get","/v2/Category/Lists",$param);

//获取逐级类目
//$param = ['parent_id'=>0,'source'=>2];
//$response = $supplyClient->getApiResponse("get","/v2/Category/GetCategory",$param);

//批量更新商品
//$param = ['goodsIds'=>'890394,890395'];
//$response = $supplyClient->getApiResponse("get","/v2/Goods/GetBulkGoodsMessage",$param);

//在线选品
//$param = ['page'=>1, 'limit'=>20, 'source'=>2];
//$response = $supplyClient->getApiResponse("get","/v2/GoodsStorage/Lists",$param);

//选品库增加商品
//$param = ['goods_ids'=>'890394,891827'];
//$response = $supplyClient->getApiResponse("get","/v2/GoodsStorage/Add",$param);

//选品库商品列表
//$param = ['page'=>1, 'limit'=>20, 'source'=>2];
//$response = $supplyClient->getApiResponse("get","/v2/GoodsStorage/MyLists",$param);

//选品库移除商品
//$param = ['goods_ids'=>'891827'];
//$response = $supplyClient->getApiResponse("get","/v2/GoodsStorage/Remove",$param);


var_dump($response);