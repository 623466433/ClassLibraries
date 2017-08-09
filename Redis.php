<?php 
/*Redis缓存类*/
namespace cache;

/**
*Redis缓存
*/
class Redis
{
	static $conn;

	public function __construct($host,$port,$is_pconnect = false)
	{
		self::$conn = new \Redis();
		$connect = $is_pconnect ? 'pconnect' : 'connect';
		self::$conn->$connect($host, $port);
	}

	public function set($name, $value, $expire = 600){
		$value = serialize($value);
		if (is_int($expire)) {
			$result = self::$conn->setex($name, $expire, $value);
		}else{
			$result = self::$conn->set($name, $value);
		}
		if($result){
			return true;
		}else{
			return false;
		}
	}

	public function get($name){
		$value = self::$conn->get($name);
		$value = unserialize($value);
		return $value;
	}

	public function delete($name){
		self::$conn->delete($name);
	}

	public function replace($name, $value, $expire = 600){
		$value = serialize($value);
		if (is_int($expire)) {
			$result = self::$conn->setex($name, $expire, $value);
		}else{
			$result = self::$conn->set($name, $value);
		}
		if($result){
			return true;
		}else{
			return false;
		}
	}

	public function clear(){
		self::$conn->flushAll();
	}
}
$redis = new \cache\Redis('127.0.0.1',6379);
$redis->set("abc", "ok,this is string");
var_dump($redis->get('abc'));
$arr = [1,2,3,4,5];
$redis->set('array',$arr,10);
var_dump($redis->get('array'));
 ?>