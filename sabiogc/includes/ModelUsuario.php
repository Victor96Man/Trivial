<?php
require_once "ConnectDB.php";
class ModelUsuario
{
	private static $instancia;
	private $_mngDB;

	private function __construct() {
		try {
			$conexion = new ConnectDB();
			$this->_mngDB = $conexion->get_mngDB();
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage();
			die();
		}
	}

	public static function singleton() {
		if (!isset(self::$instancia)) {
			$miclase = __CLASS__;
			self::$instancia = new $miclase;
		}
		return self::$instancia;
	}

	//Función que devuelve un usuario
	public function login($usuario, $password) {
		$result = false;
		try {
			$sql = "SELECT `id`, `usuario`, `password` FROM `expertos` WHERE `usuario` = :usuario and `password` = :password and `trash` = '0'";
			$query = $this->_mngDB->prepare($sql);
			$query->bindParam('usuario', $usuario);
			$query->bindParam('password', $password);
			$query->execute();
			$this->_mngDB = null;
			//Comprobamos si existe el usuario
			if ($query->rowCount() == 1) {
				$result = $query->fetchAll(PDO::FETCH_ASSOC);
			}
		} catch (PDOException $e) {
			$e->getMessage();
		}
		return $result;
	}

}