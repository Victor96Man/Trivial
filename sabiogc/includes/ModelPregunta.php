<?php
require_once "ConnectDB.php";
class ModelPregunta 
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

	//Función que devuelve una pregunta
	public function getPregunta($id) {
		$result = false;
		try {
			$sql = 'SELECT `pregunta`, `Objeto`, `tipoObjeto`, `categoria`, `nivel` FROM `preguntas` WHERE `id` = :id';
			$query = $this->_mngDB->prepare($sql);
			$query->bindParam('id', $id);
			$query->execute();
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			$this->_mngDB = null;
		} catch (PDOException $e) {
			$e->getMessage();
		}
		return $result;
	}

	//Función que devuelve las respuestas de una pregunta
	public function getRespuestas($id) {
		$result = false;
		try {
			$sql = 'SELECT `respuesta`, `valida` FROM `respuestas` WHERE `idPregunta` = :id';
			$query = $this->_mngDB->prepare($sql);
			$query->bindParam('id', $id);
			$query->execute();
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			$this->_mngDB = null;
		} catch (PDOException $e) {
			$e->getMessage();
		}
		return $result;
	}

	//Función que devuelve las últimas preguntas
	public function getPreguntas($id, $limite) {
		$result = false;
		try {
			$sql = 'SELECT `id`, `pregunta`, `categoria`, `nivel` FROM `preguntas` WHERE `categoria` IN (SELECT `categoria` FROM `expcategorias` WHERE `idExperto` = :id) ORDER BY `id` DESC LIMIT '.$limite.'';
			$query = $this->_mngDB->prepare($sql);
			$query->bindParam('id', $id);
			$query->execute();
			if ($query->rowCount() != 0) {
				$result = $query->fetchAll(PDO::FETCH_ASSOC);
			}
			$this->_mngDB = null;
		} catch (PDOException $e) {
			$e->getMessage();
		}
		return $result;
	}

	//Función que devuelve las últimas quince preguntas
	public function getPreguntasAdmin() {
		$result = false;
		try {
			$sql = 'SELECT `id`, `pregunta`, `categoria`, `nivel` FROM `preguntas` ORDER BY `id` DESC LIMIT 15';
			$query = $this->_mngDB->prepare($sql);
			$query->execute();
			if ($query->rowCount() != 0) {
				$result = $query->fetchAll(PDO::FETCH_ASSOC);
			}
			$this->_mngDB = null;
		} catch (PDOException $e) {
			$e->getMessage();
		}
		return $result;
	}

	//Función que devuelve todos los niveles de las preguntas
	public function getNivel() {
		$result = false;
		try {
			$sql = 'SELECT `nivel` FROM `niveles`';
			$query = $this->_mngDB->prepare($sql);
			$query->execute();
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			$this->_mngDB = null;
		} catch (PDOException $e) {
			$e->getMessage();
		}
		return $result;
	}

	//Función para eliminar una pregunta
	public function delPregunta($id) {
		$result = false;
		try {
			$sql = 'DELETE FROM `respuestas` WHERE `idPregunta` = :id';
			$query = $this->_mngDB->prepare($sql);
			$query->bindParam('id', $id);
			$result = $query->execute();
			$sql = 'DELETE FROM `preguntas` WHERE `id` = :id';
			$query = $this->_mngDB->prepare($sql);
			$query->bindParam('id', $id);
			$result = $query->execute();
			$this->_mngDB = null;
		} catch (PDOException $e) {
			$e->getMessage();
		}
		return $result;
	}

	//Función para insertar una pregunta
	public function insPregunta($valores, $respuestas) {
		try {
			$sql = 'INSERT INTO `preguntas`(`pregunta`,`Objeto`,`tipoObjeto`,`categoria`,`nivel`,`idExperto`) VALUES (:pregunta,:objeto,:tipoObjeto,:categoria,:nivel,:idExperto)';
			include("./funciones/comprobarObjeto.php");
			$query = $this->_mngDB->prepare($sql);
			$query->bindParam('pregunta', $valores['pregunta']);
			$query->bindParam('objeto', $objeto);
			$query->bindParam('tipoObjeto', $tipoObjeto);
			$query->bindParam('categoria', $valores['categoria']);
			$query->bindParam('nivel', $valores['nivel']);
			$query->bindParam('idExperto', $valores['idExperto']);
			$query->execute();
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			if (empty($result)) {
				$sql = 'SELECT `id` FROM `preguntas` WHERE `pregunta` = :pregunta';
				$add = 'INSERT INTO `respuestas`(`respuesta`,`idPregunta`,`valida`) VALUES (:respuesta,('.$sql.'),:valida)';
				foreach ($respuestas as $key => $value) {
					if (($key+1) == $valores['valida']) {
						$valida = "1";
					} else {
						$valida = "0";
					}
					$query = $this->_mngDB->prepare($add);
					$query->bindParam('respuesta', $value);
					$query->bindParam('pregunta', $valores['pregunta']);
					$query->bindParam('valida', $valida);
					$query->execute();
				}
				subirArchivo($objeto, $ruta);
			}
			$this->_mngDB = null;
		} catch (PDOException $e) {
			$e->getMessage();
		}
		return $result;
	}

	//Función para editar una pregunta
	public function updPregunta($oldPregunta, $oldRespuestas, $oldObjeto, $oldTipo, $delObjeto=0, $valores) {
		if ($delObjeto == 1 || $valores['objeto'] != "") {
			$extension = pathinfo($oldObjeto);
			if ($extension['extension']=="png" || $extension['extension']=="jpg" || $extension['extension']=="jpeg" || $extension['extension']=="gif") {
				unlink("./uploads/imagenes/".$oldObjeto);
			} else if ($extension['extension']=="mp3" || $extension['extension']=="ogg" || $extension['extension']=="wav" || $extension['extension']=="midi") {
				unlink("./uploads/audios/".$oldObjeto);
			} else if ($extension['extension']=="avi" || $extension['extension']=="mp4" || $extension['extension']=="mpeg" || $extension['extension']=="mpg") {
				unlink("./uploads/videos/".$oldObjeto);
			}
			include("./funciones/comprobarObjeto.php");
			subirArchivo($objeto, $ruta);
			$valores['objeto'] = $objeto;
			$valores['tipoObjeto'] = $tipoObjeto;
		} else {
			$valores['objeto'] = $oldObjeto;
			$valores['tipoObjeto'] = $oldTipo;
		}
		try {
			$sql = 'UPDATE `preguntas` SET `pregunta`=:pregunta,`Objeto`=:objeto,`tipoObjeto`=:tipoObjeto,`categoria`=:categoria,`nivel`=:nivel,`idExperto`=:idExperto WHERE `pregunta` = :oldPregunta';
			$query = $this->_mngDB->prepare($sql);
			$query->bindParam('pregunta', $valores['pregunta']);
			$query->bindParam('objeto', $valores['objeto']);
			$query->bindParam('tipoObjeto', $valores['tipoObjeto']);
			$query->bindParam('categoria', $valores['categoria']);
			$query->bindParam('nivel', $valores['nivel']);
			$query->bindParam('idExperto', $valores['idExperto']);
			$query->bindParam('oldPregunta', $oldPregunta);
			$result = $query->execute();
			if (!empty($result)) {
				foreach ($valores['respuestas'] as $key => $value) {
					if (($key+1) == $valores['valida']) {
						$valida = "1";
					} else {
						$valida = "0";
					}
					$sql = 'UPDATE `respuestas` SET `respuesta`=:respuesta,`valida`=:valida WHERE `idPregunta` = (SELECT `id` FROM `preguntas` WHERE `pregunta` = :pregunta) AND `respuesta` = :oldRespuesta';
					$query = $this->_mngDB->prepare($sql);
					$query->bindParam('respuesta', $value);
					$query->bindParam('valida', $valida);
					$query->bindParam('pregunta', $valores['pregunta']);
					$query->bindParam('oldRespuesta', $oldRespuestas[$key]);
					$result = $query->execute();
				}
			}
			$this->_mngDB = null;
		} catch (PDOException $e) {
			$e->getMessage();
		}
		return $result;
	}

	//Función para la búsqueda de preguntas
	public function buscarPreguntasAdmin($patron) {
		$result = false;
		try {
			$sql = "SELECT `id`, `pregunta`, `categoria`, `nivel` FROM `preguntas` WHERE `pregunta` LIKE '%$patron%'";
			$query = $this->_mngDB->prepare($sql);
			$query->execute();
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			$this->_mngDB = null;
		} catch (PDOException $e) {
			$e->getMessage();
		}
		return $result;
	}

	//Función para la búsqueda de preguntas
	public function buscarPreguntas($idExperto, $patron) {
		$result = false;
		try {
			$sql = "SELECT `id`, `pregunta`, `categoria`, `nivel` FROM `preguntas` WHERE `pregunta` LIKE '%$patron%' AND `categoria` IN (SELECT `categoria` FROM `expcategorias` WHERE `idExperto` = :id)";
			$query = $this->_mngDB->prepare($sql);
			$query->bindParam('id', $idExperto);
			$query->execute();
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			$this->_mngDB = null;
		} catch (PDOException $e) {
			$e->getMessage();
		}
		return $result;
	}

}