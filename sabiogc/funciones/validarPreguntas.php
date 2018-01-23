<?php
require_once "./includes/ConnectDB.php";
require_once "./funciones/limpiarCadena.php";

if (empty($_POST['pregunta'])) {
    $preguntaError = "Pregunta no válida.";
    $errorP = true;
} else {
    $pregunta = limpiarCadena($_POST['pregunta']);
    if (!isset($_SESSION['modPregunta'])) {
        $conexion = new ConnectDB();
        $db = $conexion->get_mngDB();
        $sql = 'SELECT COUNT(`pregunta`) AS fila FROM `preguntas` WHERE UPPER(`pregunta`) = UPPER(:pregunta)';
        $query = $db->prepare($sql);
        $query->bindParam('pregunta', $pregunta);
        $query->execute();
        $resultado = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($resultado[0]['fila'] == 1) {
            $preguntaError = "La pregunta ya existe.";
            $errorP = true;
        }
    }
	/*if (!preg_match("/^[a-z0-9áéíóúñ ?¿,.]+$/i",$pregunta)) {
        $preguntaError = "Formato de pregunta no válido.";
	    $errorP = true;
	}*/
}

if (empty($_POST['respuesta1'])) {
    $respuesta1Error = "Respuesta no válida.";
    $errorR1 = true;
} else {
    $respuesta1 = limpiarCadena($_POST['respuesta1']);
    /*if (!preg_match("/^[a-zA0-9áéíóúñÁÉÓÍÚ ?¿,.]+$/i",$respuesta1)) {
        $respuesta1Error = "Formato de respuesta no válido.";
        $errorR1 = true;
    }*/
}

if (empty($_POST['respuesta2'])) {
    $respuesta2Error = "Respuesta no válida.";
    $errorR2 = true;
} else {
    $respuesta2 = limpiarCadena($_POST['respuesta2']);
    /*if (!preg_match("/^[a-zA0-9áéíóúñÁÉÓÍÚ ?¿,.]+$/i",$respuesta2)) {
        $respuesta2Error = "Formato de respuesta no válido.";
        $errorR2 = true;
    }*/
}

if (empty($_POST['respuesta3'])) {
    $respuesta3Error = "Respuesta no válida.";
    $errorR3 = true;
} else {
    $respuesta3 = limpiarCadena($_POST['respuesta3']);
    /*if (!preg_match("/^[a-zA0-9áéíóúñÁÉÓÍÚ ?¿,.]+$/i",$respuesta3)) {
        $respuesta3Error = "Formato de respuesta no válido.";
        $errorR3 = true;
    }*/
}

if (empty($_POST['respuesta4'])) {
    $respuesta4Error = "Respuesta no válida.";
    $errorR4 = true;
} else {
    $respuesta4 = limpiarCadena($_POST['respuesta4']);
    /*if (!preg_match("/^[a-zA0-9áéíóúñÁÉÓÍÚ ?¿,.]+$/i",$respuesta4)) {
        $respuesta4Error = "Formato de respuesta no válido.";
        $errorR4 = true;
    }*/
}

if (empty($_POST['valida'])) {
    $validaError = "Respuesta válida vacía.";
    $errorV = true;
} else {
    $valida = limpiarCadena($_POST['valida']);
    if (!preg_match("/^[1-4]$/",$valida)) {
        $validaError = "Sólo se permite un número del 1 al 4.";
        $errorV = true;
    }
}

if (empty($_POST['dificultad'])) {
    $nivelError = "Nivel de dificultad vacío.";
    $errorN = true;
} else {
    $nivel = limpiarCadena($_POST['dificultad']);
    if (!preg_match("/^[a-zA-Z(áéíúó)?]+$/",$nivel)) {
        $nivelError = "Nivel de dificultad no válido.";
        $errorN = true;
    }
}

if (empty($_POST['categoria'])) {
    $categoriaError = "Lista de categorías vacía.";
    $errorC = true;
} else {
    $categoria = limpiarCadena($_POST['categoria']);
    /*if (!preg_match("/^[a-zA-Z(áéíúó)?]+$/",$categoria)) {
        $categoriaError = "Categoría no válida.";
        $errorC = true;
    }*/
}