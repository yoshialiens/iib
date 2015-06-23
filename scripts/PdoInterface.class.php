<?php
	class PdoInterface {
		const DB_NAME = "LAA0384743-renewal";//"rankroo_db";
		const DB_ADDR = "mysql025.phy.lolipop.lan";//"mysql416.db.sakura.ne.jp";
		const DB_USER = "LAA0384743";//"rankroo";
		const DB_PASS = "iib212413";
		
		/**
		 * 
		 * @var PDO
		 */
		private $pdo = false;
		private $pdo_statement = null;
		
		private function __construct(){
			
			$dsn = "mysql:dbname=" . PdoInterface::DB_NAME . ";host=" . PdoInterface::DB_ADDR;
			$username = PdoInterface::DB_USER;
			$passwd   = PdoInterface::DB_PASS;
			
			//ローカル環境用
			if($_SERVER['SERVER_NAME'] == 'rankroo.localhost'){
				$dsn = "mysql:dbname=rankroo_db;host=localhost";
				$username = "cheki_user";
				$passwd   = "cheki_pw";
			}
			
			$options = array(
				PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
			);
			try{
				$this->pdo = new PDO($dsn, $username, $passwd, $options);
			}catch(PDOException $e){
				echo $e->getMessage();
			}
		}
		private static $instance = null;
		/**
		 * 
		 * @return PdoInterface
		 */
		public static function getInstance(){
			if(is_null(self::$instance)){
				self::$instance = new self();
			}
			return self::$instance;
		}
		
		public function query($sql, $params = false){
			if($this->pdo === false){
				return false;
			}
			
			$ret = false;
			if($params === false){
				$ret = $this->pdo_statement = $this->pdo->query($sql);
			}else{
				$this->pdo_statement = $this->pdo->prepare($sql);
				$ret = $this->pdo_statement->execute($params);
			}
			return $ret;
		}
		
		public function fetchColumn($column_number = null){
			return $this->pdo_statement->fetchColumn($column_number);
		}
		
		public function fetch_assoc(){
			return $this->pdo_statement->fetch(PDO::FETCH_ASSOC);
		}
		
		public function lastInsertId(){
			return $this->pdo->lastInsertId();
		}
		
		public function beginTransaction(){
			return $this->pdo->beginTransaction();
		}
		public function commitTransaction(){
			return $this->pdo->commit();
		}
		public function rollbackTransaction(){
			return $this->pdo->rollback();
		}
		
		public function errorInfo()
		{
			return $this->pdo_statement->errorInfo();
		}
	}