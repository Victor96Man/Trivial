<?php
require_once "ConnectDB.php";
class ModelCategoria 
{
	private $_mngDB;

	public function __construct() {
		try {
			$conexion = new ConnectDB();
			$this->_mngDB = $conexion->get_mngDB();
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage();
			die();
		}
	}

	//Devuelve el número total de columnas
	public function numCategorias() {
		$result = false;
		try {
			$sql = 'SELECT COUNT(*) FROM `categorias`';
			$query = $this->_mngDB->prepare($sql);
			$query->execute();
			$result = $query->fetchcolumn();
			$this->_mngDB = null;
		} catch (PDOException $e) {
			$e->getMessage();
		}
		return $result;
	}

	//Función que devuelve todas las categorias
	public function getCategorias() {
		$result = false;
		try {
			$sql = 'SELECT `categoria` FROM `categorias`';
			$query = $this->_mngDB->prepare($sql);
			$query->execute();
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			$this->_mngDB = null;
		} catch (PDOException $e) {
			$e->getMessage();
		}
		return $result;
	}

	//Función para paginado
	public function getCategoriasPag($from_record_num, $records_per_page) {
		$result = false;
		try {
			$sql = 'SELECT `categoria` FROM categorias ORDER BY categoria ASC LIMIT '.$from_record_num.' , '.$records_per_page.'';
			$query = $this->_mngDB->prepare($sql);
			$query->execute();
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			$this->_mngDB = null;
		} catch (PDOException $e) {
			$e->getMessage();
		}
		return $result;
	}

	//Función que devuelve una categoria
	public function getCategoria($categoria) { //Recibe como parámetro la clave primaria
		$result = false;
		try {
			$sql = 'SELECT * FROM `categorias` WHERE `categoria` = :categoria';
			$query = $this->_mngDB->prepare($sql);
			$query->bindParam('categoria', $categoria);
			$query->execute();
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			$this->_mngDB = null;
		} catch (PDOException $e) {
			$e->getMessage();
		}
		return $result;
	}

	//Función para eliminar una categoria
	public function delCategoria($categoria) {
		$result = false;
		try {
			$sql = 'DELETE FROM `categorias` WHERE `categoria` = :categoria';
			$query = $this->_mngDB->prepare($sql);
			$query->bindParam('categoria', $categoria);
			$result = $query->execute();
			$this->_mngDB = null;
		} catch (PDOException $e) {
			$e->getMessage();
		}
		return $result;
	}

	//Función para insertar una categoria
	public function insCategoria($valores) { //Recibe como parámetro un array de valores
		try {
			$sql = 'INSERT INTO `categorias`(`categoria`) VALUES (:categoria)';
			$query = $this->_mngDB->prepare($sql);
			$query->bindParam('categoria', $valores['categoria']);
			$result = $query->execute();
			$this->_mngDB = null;
		} catch (PDOException $e) {
			$e->getMessage();
		}
		return $result;
	}

	//Función para editar una categoria
	public function updCategorias($oldCategoria, $valores) {
		try {
			$newCategoria = $valores['categoria'];
			$sql = 'UPDATE `categorias` SET `categoria` = :newcategoria WHERE `categoria` = :categoria';
			$query = $this->_mngDB->prepare($sql);
			$query->bindParam('newcategoria', $newCategoria);
			$query->bindParam('categoria', $oldCategoria);
			$result = $query->execute();
			$this->_mngDB = null;
		} catch (PDOException $e) {
			$e->getMessage();
		}
		return $result;
	}

	//Función para la búsqueda de categorías
	public function buscarCategorias($patron) {
		$result = false;
		try {
			$sql = "SELECT `categoria` FROM `categorias` WHERE `categoria` LIKE '%$patron%' ORDER BY `categoria` ASC";
			$query = $this->_mngDB->prepare($sql);
			$query->execute();
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			$this->_mngDB = null;
		} catch (PDOException $e) {
			$e->getMessage();
		}
		return $result;
	}

}