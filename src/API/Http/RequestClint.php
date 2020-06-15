<?php
/**
 * Created by PhpStorm.
 * User: gu
 * Date: 2020/6/12
 * Time: 2:14 PM
 */
namespace API\Http;

use API\Core\Base;

class RequestClint
{

	public function __construct()
	{

	}

	public static function get($methodOrUri,Base $Request)
	{
		if(!self::verification($Request)) {
			return json_encode('appKey 或 appSecret 不能为空');
		}

		$Request->addParam('Api-Sign', self::setSign($Request->getAllParam(), $Request->getAppSecret()));

		$serverUrl = self::richRequest($methodOrUri, $Request);

		$serverUrl .= (strpos($serverUrl,'?') === false ?'?':'&') . $Request->toQueryString();

		$headers = array(
			'Api-App-Key' => $Request->paramMap['Api-App-Key'],
			'Api-Time-Stamp' => $Request->paramMap['Api-Time-Stamp'],
			'Api-Nonce' => $Request->paramMap['Api-Nonce'],
			'Api-Sign'=> $Request->paramMap['Api-Sign']
		);
		return self::curl_request($serverUrl, false ,$headers);
	}

	public static function getBody($methodOrUri,Base $Request)
	{
		if(!self::verification($Request)) {
			return json_encode('appKey 或 appSecret 不能为空');
		}

		$Request->addParam('Api-Sign', self::setSign($Request->getAllParam(), $Request->getAppSecret() ,$Request->body));
		$serverUrl = self::richRequest($methodOrUri, $Request);

		$headers = array(
			'Api-App-Key' => $Request->paramMap['Api-App-Key'],
			'Api-Time-Stamp' => $Request->paramMap['Api-Time-Stamp'],
			'Api-Nonce' => $Request->paramMap['Api-Nonce'],
			'Api-Sign'=> $Request->paramMap['Api-Sign']
		);
		return self::curl_get_body_request($serverUrl, $Request->body ,$headers);
	}

	public static function post($methodOrUri,Base $Request)
	{

		if(!self::verification($Request)) {
			return json_encode('appKey 或 appSecret 不能为空');
		}

		$Request->addParam('Api-Sign', self::setSign($Request->getAllParam(), $Request->getAppSecret() ,$Request->body));
		$serverUrl = self::richRequest($methodOrUri, $Request);

		$headers = array(
			'Api-App-Key' => $Request->paramMap['Api-App-Key'],
			'Api-Time-Stamp' => $Request->paramMap['Api-Time-Stamp'],
			'Api-Nonce' => $Request->paramMap['Api-Nonce'],
			'Api-Sign'=> $Request->paramMap['Api-Sign']
		);
		return self::curl_request($serverUrl, $Request->body,$headers);
	}



	public static function patch($methodOrUri,Base $Request)
	{

		if(!self::verification($Request)) {
			return json_encode('appKey 或 appSecret 不能为空');
		}

		$Request->addParam('Api-Sign', self::setSign($Request->getAllParam(), $Request->getAppSecret() ,$Request->body));
		$serverUrl = self::richRequest($methodOrUri, $Request);

		$headers = array(
			'Api-App-Key' => $Request->paramMap['Api-App-Key'],
			'Api-Time-Stamp' => $Request->paramMap['Api-Time-Stamp'],
			'Api-Nonce' => $Request->paramMap['Api-Nonce'],
			'Api-Sign'=> $Request->paramMap['Api-Sign']
		);
		return self::curl_patch_request($serverUrl, $Request->body,$headers);
	}


	public static function verification(Base $Request)
	{
		if ($Request->getAppKey() && $Request->getAppSecret()) {
			return true;
		}

		return false;
	}


	/**
	 * 请求接口地址
	 * @param $methodOrUri
	 * @param $JdRequest
	 * @return string
	 */
	public static  function richRequest($methodOrUri, Base $Request)
	{

		if (strpos($methodOrUri, $Request->getServerRoot()) !== false) {
			return $methodOrUri;
		}

		if (strpos($methodOrUri, 'http://') !== false || strpos($methodOrUri, 'https://') !== false) {
			return $methodOrUri;
		}

		$serverUrl = rtrim($Request->getServerRoot(), '/') . '/'. ltrim($methodOrUri, '/');

		return $serverUrl;
	}

	/**
	 * 签名
	 * @param $data
	 * @param $appsecret
	 * @return string
	 */
	public static function setSign($data, $appsecret ,$body = '')
	{
		ksort($data);


		$str_key="";
		foreach ($data as $k=>$v){
			$str_key.=$k.$v;
		}
		$str_key .= $appsecret;
		$str_key .= $body;

		$sign = strtoupper(md5(sha1($str_key)));

		return $sign;
	}


	/**
	 * 设置请求头
	 * @return array
	 */
	public static function setHeaders()
	{

		$headers = array();

		$headers['Content-Type'] = 'application/x-www-form-urlencoded';

//        $headers['iversion'] =  self::get_client_browser()[1];
//        if (false) {
//            $headers['Authorization'] = '';
//        }

		return $headers;

	}

	//获取客户端浏览器
	public static function get_client_browser()
	{
		$sys = $_SERVER['HTTP_USER_AGENT'];  //获取用户代理字符串
		if (stripos($sys, "Firefox/") > 0) {
			preg_match("/Firefox\/([^;)]+)+/i", $sys, $b);
			$exp[0] = "Firefox";
			$exp[1] = $b[1];  //获取火狐浏览器的版本号
		} elseif (stripos($sys, "Maxthon") > 0) {
			preg_match("/Maxthon\/([\d\.]+)/", $sys, $aoyou);
			$exp[0] = "傲游";
			$exp[1] = $aoyou[1];
		} elseif (stripos($sys, "MSIE") > 0) {
			preg_match("/MSIE\s+([^;)]+)+/i", $sys, $ie);
			$exp[0] = "IE";
			$exp[1] = $ie[1];  //获取IE的版本号
		} elseif (stripos($sys, "OPR") > 0) {
			preg_match("/OPR\/([\d\.]+)/", $sys, $opera);
			$exp[0] = "Opera";
			$exp[1] = $opera[1];
		} elseif(stripos($sys, "Edge") > 0) {
			//win10 Edge浏览器 添加了chrome内核标记 在判断Chrome之前匹配
			preg_match("/Edge\/([\d\.]+)/", $sys, $Edge);
			$exp[0] = "Edge";
			$exp[1] = $Edge[1];
		} elseif (stripos($sys, "Chrome") > 0) {
			preg_match("/Chrome\/([\d\.]+)/", $sys, $google);
			$exp[0] = "Chrome";
			$exp[1] = $google[1];  //获取google chrome的版本号
		} elseif(stripos($sys,'rv:')>0 && stripos($sys,'Gecko')>0){
			preg_match("/rv:([\d\.]+)/", $sys, $IE);
			$exp[0] = "IE";
			$exp[1] = $IE[1];
		}else {
			$exp[0] = "未知浏览器";
			$exp[1] = "000";
		}
		return $exp;
	}

	//http 请求
	public static  function curl_request($url, $post, $headers=null, $timeout=120, $json=false){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);


		if($headers!=null) {
			$headerArray=array();
			array_push($headerArray,'Content-Type: application/json');
			foreach ($headers as  $key => $value) {
				array_push($headerArray,$key.":".$value);
			}
			curl_setopt($curl, CURLOPT_HTTPHEADER,  $headerArray);
		}

		if($post) {
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, is_array($post)?http_build_query($post):$post);
		}

		$TLS = substr($url, 0, 8) == "https://" ? true : false;

		if($TLS) {
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
		}

		// 关闭SSL验证
//        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
//        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);



		curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
		$data = curl_exec($curl);
		if (curl_errno($curl)) {
			return curl_error($curl);
		}
		curl_close($curl);


		if ($json){

			return json_decode($data,true);
		}
		return $data;

	}

	public static  function curl_patch_request($url, $post, $headers=null, $timeout=120, $json=false){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($curl, CURLOPT_CUSTOMREQUEST, "PATCH");

		if($headers!=null) {
			$headerArray=array();
			array_push($headerArray,'Content-Type: application/json');
			foreach ($headers as  $key => $value) {
				array_push($headerArray,$key.":".$value);
			}
			curl_setopt($curl, CURLOPT_HTTPHEADER,  $headerArray);
		}

		curl_setopt($curl, CURLOPT_POSTFIELDS, is_array($post)?http_build_query($post):$post);

		$TLS = substr($url, 0, 8) == "https://" ? true : false;

		if($TLS) {
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
		}

		// 关闭SSL验证
//        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
//        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);



		curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
		$data = curl_exec($curl);
		if (curl_errno($curl)) {
			return curl_error($curl);
		}
		curl_close($curl);


		if ($json){

			return json_decode($data,true);
		}
		return $data;

	}

	public static  function curl_get_body_request($url, $post, $headers=null, $timeout=120, $json=false){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);


		if($headers!=null) {
			$headerArray=array();
			array_push($headerArray,'Content-Type: application/json');
			foreach ($headers as  $key => $value) {
				array_push($headerArray,$key.":".$value);
			}
			curl_setopt($curl, CURLOPT_HTTPHEADER,  $headerArray);
		}

		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($curl, CURLOPT_POSTFIELDS, is_array($post)?http_build_query($post):$post);

		$TLS = substr($url, 0, 8) == "https://" ? true : false;

		if($TLS) {
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
		}

		// 关闭SSL验证
//        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
//        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);



		curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
		$data = curl_exec($curl);
		if (curl_errno($curl)) {
			return curl_error($curl);
		}
		curl_close($curl);


		if ($json){

			return json_decode($data,true);
		}
		return $data;

	}


}