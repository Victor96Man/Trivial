<?php 
require_once "ConnectDB.php";
// Clase Preguntas que sólo implementa la recuperación con respuestas.

class Preguntas
{
// Conexión
private $conexion;

// Constructor.
public function __construct()
    {
        try {
			$this->conexion = new ConnectDB();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
            die();
        }
    }

public function getCategorias($categorias="")
{
	try{
		if ($categorias == "") {
			$sql = "SELECT * FROM categorias";
			$resultado = $this->conexion->query($sql); // Lanzamos consulta
			return $resultado;

		}
	}catch(PDOException $e){

	}
}

/* Recibe como parámetro opcional categoría.
Devuelve array con preguntas y respuestas asociadas.
LLamada sin parametro devuelve todas
Con parámetro filtra la categoría.
*/
public function getPreguntas($categoria="") 
{
    $parametros = array();  // Parametros.
	$arrayClaves = array(); // Indices para el array asociativo de cada pregunta
	$arrayValores = array();// Valores que corresponden a los índices anteriores.
	$arrayPreguntas = array(); // Preguntas devueltas. Array indexado asociativo.
	try {
	    if ($categoria =="") {   
			$sqlp = "Select * from preguntas where trash = 0"; // Sin categoría carga todas las preguntas
			
		}
		else {
		   $sqlp = "Select * from preguntas where trash = 0 and categoria=:patronP"; //Consulta filtrada
		   $parametros['patronP']=$categoria;

		}
		$sqlr = "Select * from respuestas where trash = 0 and idPregunta=:patronR";  //Consulta para recuperar respuestas
		$resultado = $this->conexion->query($sqlp, $parametros); // Lanzamos consulta
		
		
		foreach ($resultado as $fila){    // Para cada pregunta 
			//Iniciamos los arrays en cada ciclo del bucle
			$arrayClaves = array();   
			$arrayValores = array();
			//Añadimos campo y valor en el array correspondiente.
			foreach ($fila as $key=>$valor) {
				$arrayClaves[] = $key;
				$arrayValores[] = $valor;
			}
			// Buscamos las respuestas a la pregunta en la que estamos.
			$parametros['patronR'] = $fila['id'];
			$respuestas = $this->conexion->query($sqlr, $parametros);
			$i = 0; 
			//Añadimos campo y valor en el array correspodiente
			//Los campos para las respuesta falsas se codifican  "Respuesta n"
			//Para la respuesta correcta el campo lo llamamos "Correcta"
			//En caso de varias correctas tendriamos que codificar "Correcta n" 
			foreach ($respuestas as $filaRespuesta) {
				$arrayValores[] = $filaRespuesta['respuesta'];
				if ($filaRespuesta['valida'] == 1) {
					$arrayClaves[] = "Correcta";
				}
				else {
					$i++;
					$arrayClaves[] = "Respuesta $i";	
				}
			}
			// Añadimos un nuevo elemento tipo array al arrayPreguntas
			$arrayPreguntas[] = array_combine($arrayClaves, $arrayValores);
		}
		return $arrayPreguntas;
	} 
	catch (PDOException $e) {
		$e->getMessage();
		return false;
	}
}
	function generarCategoriasPreguntas($listaCategorias,$arrayPreguntas){
		if (empty($listaCategorias)) {
			$arrayResultado = $this->getCategorias();


			foreach ($arrayResultado as $key => $value) {
				// echo $key;
				// array_push($arrayPreguntas,array());	
				array_push($listaCategorias,$value["categoria"]);
				array_push($arrayPreguntas,$this->getPreguntas($value["categoria"]));
			}
			return array($listaCategorias,$arrayPreguntas);
		}else{
			
			foreach ($listaCategorias as $key => $value) {
				array_push($arrayPreguntas,$this->getPreguntas($value));
			}

			return $arrayPreguntas;
		}
	}
}