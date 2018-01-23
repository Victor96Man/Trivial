<?php
session_start();
require_once "ModelPregunta.php";

$preguntas = new ModelPregunta();
$q = $_REQUEST['q'];
if ($q == "") {
	if ($_SESSION['perfil'] == "experto") {
		$resultado = $preguntas->getPreguntas($_SESSION['usuario'][0]['id'], 5);
	} else if ($_SESSION['perfil'] == "admin") {
		$resultado = $preguntas->getPreguntasAdmin();
	}
} else {
	if ($_SESSION['perfil'] == "experto") {
		$resultado = $preguntas->buscarPreguntas($_SESSION['usuario'][0]['id'], $q);
	} else if ($_SESSION['perfil'] == "admin") {
		$resultado = $preguntas->buscarPreguntasAdmin($q);
	}
}
$preguntas = null;
if (!empty($resultado)) {
	echo "<table class=\"table table-hover table-striped\">
			<th class=\"text-center\">Pregunta</th><th class=\"text-center\">Categoría</th><th class=\"text-center\">Nivel</th><th colspan=\"3\" class=\"text-center\">Opciones</th>";
	foreach ($resultado as $key => $value) {
		echo "<tr>";
		echo "<td>".$value['pregunta']."</td><td>".$value['categoria']."</td><td>".$value['nivel']."</td>";
		echo "<td><a href=\"./index.php?page=preguntas&id=".$value['id']."&accion=editar\" class=\"btn btn-primary\"><span class=\"glyphicon glyphicon-pencil\"></span> Editar</a></td>";
		echo "</tr>";
	}
	echo "</table>";
} else {
	if (isset($_GET['q'])) {
		echo "<br /><p>No existen preguntas que contengan la palabra <b>".$q."</b>.</p><br />";
	} else {
		echo "<br /><p>Aún no has introducido preguntas.</p><br />";
	}
}