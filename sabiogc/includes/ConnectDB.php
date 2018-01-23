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

	public function __construct() {
		//Cargamos las variables de conexión
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
			$this->_mngDB = new PDO($dsn, $this->_user, $this->_pass, 
									array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		} catch (PDOException $e) {
			printf("Conexión fallida: %s\n", $e->getMessage());
			exit();
		}
	}

	public function query($sql, $values=array()) {
		$_result = false;
		/*try {
			$_stmt = $this->_mngDB->prepare($sql);
			$_stmt->execute($values);
			$_result = $_stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			printf($e->getMessage());
			exit();
		}*/
		if (($_stmt = $this->_mngDB->prepare($sql))) {
			if (preg_match_all('/(:\w+)/', $sql, $_named, PREG_PATTERN_ORDER)) {
				$_named = array_pop($_named);
				foreach ($_named as $_param) {
					$_stmt->bindValue($_param, $values[substr($_param, 1)]);
				}
			}
			try {
				if (!$_stmt->execute()) {
					printf("Error en ejecución de consulta: %s\n", $_stmt->errorInfo());
				}
				$_result = $_stmt->fetchAll(PDO::FETCH_ASSOC);
				$_stmt->closeCursor();
			} catch (PDOException $e) {
				printf("Error en consulta: %s\n", $e->getMessage());
			}
			return $_result;
		}
	}
}