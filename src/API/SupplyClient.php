<?php
/**
 * Created by PhpStorm.
 * User: gu
 * Date: 2020/6/12
 * Time: 2:46 PM
 */
namespace API;

use API\Core\ClientException;
use API\Core\Base;
use API\Http\RequestClint;

class SupplyClient extends Base
{
	/**
	 * 构造函数
	 *
	 * 构造函数有几种情况：
	 * 一般的时候初始化使用 $supplyClient = new SupplyClient($appKey, $appSecret)
	 * 初始化使用 $supplyClient = new SupplyClient($appKey, $appSecret)
	 *
	 * @param string $appKey 从Open平台获得的appKey
	 * @param string $appSecret 从Open平台获得的appSecret
	 */
	public function __construct($appKey, $appSecret)
	{
		$appKey = trim($appKey);
		$appSecret = trim($appSecret);

		if (empty($appKey)) {
			throw new ClientException("app key id is empty");
		}
		if (empty($appSecret)) {
			throw new ClientException("app secret is empty");
		}
		parent::__construct($appSecret,$appKey);

		self::checkEnv();
	}


	public function MyGoodsLists($source,$page,$limit){
		$this->addParam('page', $page);
		$this->addParam('limit', $limit);
		$this->addParam('source', $source);
		$response = RequestClint::get('/v2/GoodsStorage/MyLists', $this);

		var_dump($response);
	}


	/**
	 * 用来检查sdk所以来的扩展是否打开
	 *
	 * @throws OssException
	 */
	public static function checkEnv()
	{
		if (function_exists('get_loaded_extensions')) {
			//检测curl扩展
			$enabled_extension = array("curl");
			$extensions = get_loaded_extensions();
			if ($extensions) {
				foreach ($enabled_extension as $item) {
					if (!in_array($item, $extensions)) {
						throw new ClientException("Extension {" . $item . "} is not installed or not enabled, please check your php env.");
					}
				}
			} else {
				throw new ClientException("function get_loaded_extensions not found.");
			}
		} else {
			throw new ClientException('Function get_loaded_extensions has been disabled, please check php config.');
		}
	}
}