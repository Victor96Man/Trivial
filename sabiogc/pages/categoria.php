<?php
if ($_SESSION['perfil'] != "admin")
	header("Location: ./index.php");
if (!isset($_SESSION['modCategoria'])) {
	$_SESSION['modCategoria'] = "";
}

require_once "./includes/ModelCategoria.php";
require_once "./funciones/limpiarCadena.php";

echo "<script src=\"./js/searchCat.js\"></script>";

$error = false;
$msgError = "";
$nuevaCategoria = "";

if (!isset($_GET['categoria'])) {
	$_GET['categoria'] = "";
}

if (isset($_SESSION['msgError']) && isset($_GET['accion'])) {
	$msgError = $_SESSION['msgError'];
}

//Comprobamos la acción a realizar en la página de categorías
if (isset($_GET['accion'])) {
	if ($_GET['accion'] == "editar") {
		$_SESSION['modCategoria'] = $_GET['categoria'];
		$nuevaCategoria = $_GET['categoria'];
		$accion = "Editar categoría";
		$btn = "editar";
	} else if ($_GET['accion'] == "eliminar") {
		$objCategoria = new ModelCategoria();
		$objCategoria->delCategoria($_GET['categoria']);
		$objCategoria = null;
		header("Location: ./index.php?page=categoria");
	} else if ($_GET['accion'] == "annadir") {
		$accion = "Añadir categoría";
		$btn = "annadir";
	}
}

if (isset($_POST['annadir'])) {
	if (empty($_POST['categoria'])) {
		$msgError = "Categoría no válida.";
		$error = true;
	} else {
		$nuevaCategoria = limpiarCadena($_POST['categoria']);
		if (!preg_match("/^[a-zA-ZáéíóúñÁÉÍÓÚÑ\s]+$/",$nuevaCategoria)) {
			$msgError = "Formato de categoría no válido, sólo se permiten letras.";
			$error = true;
		}
	}
	if (!$error) {
		$objCategoria = new ModelCategoria();
		$objCategoria->insCategoria(array('categoria'=>$nuevaCategoria));
		$objCategoria = null;
		header("Location: ./index.php?page=categoria");
	} else {
		$_SESSION['msgError'] = $msgError;
		header("Location: ./index.php?page=categoria&accion=annadir");
	}
} else if (isset($_POST['editar'])) {
	if (empty($_POST['categoria'])) {
		$msgError = "Categoría no válida.";
		$error = true;
	} else {
		$nuevaCategoria = limpiarCadena($_POST['categoria']);
		if (!preg_match("/^[a-zA-ZáéíóúñÁÉÍÓÚÑ\s]+$/",$nuevaCategoria)) {
			$msgError = "Formato de categoría no válido, sólo se permiten letras.";
			$error = true;
		}
	}
	if (!$error) {
		$objCategoria = new ModelCategoria();
		$objCategoria->updCategorias($_SESSION['modCategoria'], array('categoria'=>$nuevaCategoria));
		$objCategoria = null;
		unset($_SESSION['modCategoria']);
		header("Location: ./index.php?page=categoria");
	} else {
		$_SESSION['msgError'] = $msgError;
		header("Location: ./index.php?page=categoria&categoria=".$_SESSION['modCategoria']."&accion=editar");
	}
} else if (isset($_POST['cancelar'])) {
	unset($_SESSION['msgError']);
	unset($_SESSION['modCategoria']);
}

echo "<div class=\"container\">
		<p><span class=\"glyphicon glyphicon-user\"></span> Conectado al sistema como: ".$_SESSION['usuario'][0]['usuario']."</p>
	</div>";

if (!isset($_GET['accion'])) {
	echo "<div class=\"container\">
			<label>Buscador</label>
			<p><input type=\"text\" class=\"form form-control\" onkeyup=\"showHint(this.value)\" placeholder=\"Búsqueda\">
			<a href=\"./index.php?page=categoria&accion=annadir\" class=\"btn btn-success\"><span class=\"glyphicon glyphicon-plus\"></span> Añadir categoría</a></p>
		</div>";
	echo "<div class=\"container\">";
	echo "<h3>Categorías</h3>";
	$categoria = new ModelCategoria();
	$resultado = $categoria->getCategorias();
	/*if (isset($_POST['buscar'])) {
		$texto = limpiarCadena($_POST['search']);
		$resultado = $categoria->buscarCategorias($texto);
	} else {
		$resultado = $categoria->getCategorias();
	}*/
	$categoria = null;
	echo "<div id=\"txtHint\">";
	echo "<table class=\"table table-hover table-striped\">
			<th class=\"text-center\">Nombre</th><th colspan=\"2\" class=\"text-center\">Opciones</th>";
	foreach ($resultado as $key => $value) {
		echo "<tr>";
		echo "<td>".$value['categoria']."</td><td><a href=\"./index.php?page=categoria&categoria=".$value['categoria']."&accion=editar\" class=\"btn btn-primary\"><span class=\"glyphicon glyphicon-pencil\"></span> Editar</a> <a href=\"./index.php?page=categoria&categoria=".$value['categoria']."&accion=eliminar\" class=\"btn btn-danger\"><span class=\"glyphicon glyphicon-remove\"></span> Eliminar</a></td>";
		echo "</tr>";
	}
	echo "</table>";
	echo "</div>";
}

if (isset($_GET['accion']) && ($_GET['accion'] == "annadir" || $_GET['accion'] == "editar")) {
	echo "<form method=\"post\" action=\"" . htmlspecialchars('./index.php?page=categoria') . "\">";
	echo "<div class=\"container\">";
	echo "<h3>" . $accion . "</h3>";
	echo "<p>
			<label>Categoría</label>
			<input type=\"text\" class=\"form-control\" name=\"categoria\" value=\"".$nuevaCategoria."\" placeholder=\"Ej. Deportes\">
			<span class=\"error\">".$msgError."</span>
		  </p>";
	echo "<p>
			<input type=\"submit\" class=\"btn btn-primary\" name=\"".$btn."\" value=\"Aceptar\">
			<input type=\"submit\" class=\"btn btn-danger\" name=\"cancelar\" value=\"Cancelar\">
		</p>";
	echo "</div>";
	echo "</form>";
}
echo "</div>";