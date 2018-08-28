<?php
	class Store {
		private static $ENVIRONMENT;
		private static $DB;

		public static function LOAD_CONFIG($filename = ".env"){
			self::$ENVIRONMENT = new stdClass();
			if(!file_exists($filename)){
				return false;
			}

			$config_file = fopen($filename, 'r');
			if(!$config_file){
				return false;
			}

			while(($buffer = fgets($config_file)) !== false){
				$arr = explode('=', trim($buffer));
				if(isset($arr[0]) && isset($arr[1])){
					$key = $arr[0];
					$val = $arr[1];

					self::$ENVIRONMENT->$key = $val;
				}
			}

			return true;
		}
		public static function OPEN_DB(){
			$pdo = new PDO(
				"mysql:host=" . self::$ENVIRONMENT->DB_HOST . ";dbname=" . self::$ENVIRONMENT->DB_DATABASE . ";port=" . self::$ENVIRONMENT->DB_PORT,
				self::$ENVIRONMENT->DB_USERNAME,
				self::$ENVIRONMENT->DB_PASSWORD,
				[
					PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
				]
			);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			self::$DB = $pdo;
		}
		/**
		 *	@return PDO
		 */
		public static function DB(){
			return self::$DB;
		}
	}