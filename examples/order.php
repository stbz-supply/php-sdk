<?php
/**
 * Created by PhpStorm.
 * User: stbz
 * Date: 2020/6/17
 * Time: 4:00 PM
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


$param =[
	'address'=>[
		'consignee' => '小胜',
		'phone' => '13000000000',
		'province' => '北京市',
		'city' => '北京市',
		'area' => '丰台区',
		'street' => '卢沟桥街道',
		'description' => '和谐银座商场',
	],

	'spu' => [
		[
			'sku'=>5542227,
			'number'=>1,
		],
	],
];
//可售检测
$response = $supplyClient->getApiResponse("get","/v2/order/availableCheck",$param);
//前置校验
//$response = $supplyClient->getApiResponse("post","/v2/order/beforeCheck",$param);

//下单接口
//$param =[
//	'address'=>[
//		'consignee' => '小胜',
//		'phone' => '13000000000',
//		'province' => '北京市',
//		'city' => '北京市',
//		'area' => '丰台区',
//		'street' => '卢沟桥街道',
//		'description' => '和谐银座商场',
//	],
//
//	'spu' => [
//		[
//			'sku'=>15570,//skuId
//			'number'=>1,
//		],
//	],
//	'orderSn'=>'thirdsn20200617160900001',//商城订单号
//];

//$response = $supplyClient->getApiResponse("post","/v2/order",$param);

//失败补单
//$param = ['orderSn'=>'0200617160_2_1'];
//$response = $supplyClient->getApiResponse("patch","/v2/order",$param);

//全平台订单列表
//$param = ['page'=>1,'limit'=>20];
//$response = $supplyClient->getApiResponse("get","/v2/order",$param);
//各平台订单列表
//$source =2;//京东渠道
//$response = $supplyClient->getApiResponse("get","/v2/order/source".$source,$param);

//订单详情
//$param = [];
//$sn ="20191115204845294762_6_1_1";//三级订单号
//$response = $supplyClient->getApiResponse("get","/v2/order".$sn,$param);

//物流查询
//$param = [
//	'orderSn'=>'20200610111116', //商城订单号
//	'sku'=>4339236
//];
//
//$response = $supplyClient->getApiResponse("get","/v2/logistic",$param);

//物流查询
//$param = [];
//$response = $supplyClient->getApiResponse("get","/v2/logistic/firms",$param);

var_dump($response);