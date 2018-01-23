<?php
require_once "configDB.php"; //Lectura del fichero en la clase
class ConnectDB
{
	private $_host;
	private $_database;
	private $_user;
	private $_pass;
	private $_port;
	private $_mngDB;

	public function get_mngDB() {
		return $this->_mngDB;
	}

	//public function __construct($host='', $database='', $user='', $pass='', $port='') {
	public function __construct() {
		$this->_host = HOST;
		$this->_database = DATABASE;
		$this->_user = USER;
		$this->_pass = PASS;
		$this->_port = PORT;

		//Cadena de conexión
		$dsn = 'mysql:host=' . $this->_host . ';'
				. 'dbname=' . $this->_database . ';'
				. 'port=' . $this->_port;

		try {
			$this->_mngDB = new PDO($dsn, $this->_user, $this->_pass);
			$this->_mngDB->query("SET NAMES UTF8");
		} catch (PDOException $e) {
			printf("Conexión fallida: %s\n", $e->getMessage());
			exit();
		}
	}

	/*public function query($sql, $values=array()) {
		$_result = false;
			try {
			  $result=$this->_mngDB->prepare($sql);
			  $result->execute($values);
  			  $_result = $result->fetchAll(PDO::FETCH_ASSOC);
			} catch (PDOException $e) {
				printf("Conexión fallida: %s\n", $e->getMessage());
				exit();
			}

			return $_result;
	}*/

	public function rowCount($sql, $values=array()) {
        $_result=false;

        try{
        	$_consult = $this->_mngDB->prepare($sql);
        	$_consult->execute($values);
        	$_result=$_consult->rowCount();
        }catch(PDOException $e){
            printf("Consulta fallida: %s\n", $e->getMessage());
            exit();
        }
        return $_result;
    }

	public function query($sql, $values=array()) {
		$_result = false;
		if(($_stmt = $this->_mngDB->prepare($sql))){
			if(preg_match_all('/(:\w+)/', $sql, $_named, PREG_PATTERN_ORDER)){
				$_named=array_pop($_named);
				foreach ($_named as $_param) {
					$_stmt->bindValue($_param, $values[substr($_param, 1)]);
				}
			}

			try{
				if(!$_stmt->execute()){
					print_r($_stmt->errorInfo());
				}
				$_result = $_stmt->fetchAll(PDO::FETCH_ASSOC);
				//$_stmt->closeCursor();
			}catch(PDOException $e){
				printf("Conexión fallida: %s\n", $e->getMessage());
			}

			return $_result;
		}
	}
	
}