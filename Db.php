<?php
namespace DB;

class Db
{
	static $conn;
	public $lastId;

	public function __construct($dsn, $username, $password)
	{
		try {
			self::$conn = new \PDO($dsn, $username, $password);
			self::$conn->query('SET NAMES utf8');	
		} catch (\PDOException $e) {
			exit('数据库连接失败，错误信息：'. $e->getMessage());
		}	
	}

	public function query($sql){
		if (!is_string($sql))
			return false;

		$result = self::$conn->query($sql);

		if(!$result)
			return false;

		$result = $result->fetchAll(PDO::FETCH_OBJ);

		return $result;
	}

	public function insert($sql)
	{
		if (!is_string($sql))
			return false;

		$result = self::$conn->exec($sql);

		if(!$result)
			return false;

		return $result;
	}

	public function lastId()
	{
		$this->lastId = self::$conn->lastInsertId();
		return $this->lastId;
	}

	public function find($sql)
	{
		if (is_string($sql))
			return false;

		$result = self::query($sql);

		$result = $result->fetch();

		if(!$result)
			return false;

		return $result;
	}

	public function beginTransaction()
	{
		self::$conn->beginTransaction();
	}

	public function rollBack()
	{
		self::$conn->rollBack();
	}

	public function commit()
	{
		self::$conn->commit();
	}

	public function prepare($sql, $arr)
	{
		if(!is_string($sql))
			return false;
		$stmp = self::$conn->prepare($sql);
		foreach ($arr as $key => $value) {
			$stmp->bindParam($key,$value);
		}
		$stmp->execute();
		$stmp->commit();
	}
}
$dsn = "mysql:host=192.168.10.10;dbname=test";
$username = "homestead";
$password = "secret";
$pdo = new \DB\Db($dsn, $username, $password);
$result = $pdo->query('show tables');
var_dump($result);
