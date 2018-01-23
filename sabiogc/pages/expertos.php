<?php
if ($_SESSION['perfil'] != "admin") {
	header("Location: ./index.php");
}
unset($_SESSION['msgError']);

require_once "./includes/ModelExperto.php";
require_once "./includes/ModelCategoria.php";
require_once "./funciones/limpiarCadena.php";

echo "<script src=\"./js/searchExp.js\"></script>";

$errorN = $errorU = $errorP = $errorPR = $errorE = $errorC = false;
$id = $nombre = $usuario = $password = $passwordR = $email = "";
$nombreError = $usuarioError = $passwordError = $passwordRError = $emailError = $categoriaError = $msgError = "";
$categorias = array();

if (isset($_SESSION['msgError']) && isset($_GET['usuario'])) {
	if ($_SESSION['modExperto'] != $_GET['usuario']) {
		$_SESSION['msgError'] = "";
	} else {
		$msgError = $_SESSION['msgError'];
	}
}

//Comprobamos la acción a realizar en la página de expertos
if (isset($_GET['accion'])) {
	if ($_GET['accion'] == "editar") {
		$objExperto = new ModelExperto();
		$experto = $objExperto->getExperto($_GET['usuario']);
		$objExperto = null;
		$id = $experto[0]['id'];
		$nombre = $experto[0]['nombre'];
		$usuario = $experto[0]['usuario'];
		$password = $experto[0]['password'];
		$email = $experto[0]['email'];
		$_SESSION['modExperto'] = $_GET['usuario'];
		$accion = "Editar experto";
		$btn = "editar";
	} else if ($_GET['accion'] == "eliminar") {
		$objExperto = new ModelExperto();
		$objExperto->delExperto($_GET['usuario']);
		$objExperto = null;
		header("Location: ./index.php?page=expertos");
	} else if ($_GET['accion'] == "annadir") {
		$accion = "Añadir experto";
		$btn = "annadir";
	}
}

if (isset($_POST['annadir']) || isset($_POST['editar'])) {
	include("./funciones/validarExpertos.php");
	if (isset($_POST['annadir'])) {
		if (!$errorN && !$errorU && !$errorP && !$errorPR && !$errorE && !$errorC) {
			$objExperto = new ModelExperto();
			$objExperto->insExperto(array('nombre'=>$nombre,'usuario'=>$usuario,'password'=>$password,'email'=>$email), $categorias);
			$objExperto = null;
			header('Location: ./index.php?page=expertos');
		}
	} else if (isset($_POST['editar'])) {
		if (!$errorN && !$errorU && !$errorP && !$errorPR && !$errorE && !$errorC) {
			$objExperto = new ModelExperto();
			$objExperto->updExperto($_SESSION['modExperto'], array('nombre'=>$nombre,'usuario'=>$usuario,'password'=>$password,'email'=>$email), $categorias);
			$objExperto = null;
			header('Location: ./index.php?page=expertos');
		} else {
			$_SESSION['msgError'] = "Error al editar el usuario.";
			header('Location: ./index.php?page=expertos&usuario='.$_SESSION['modExperto'].'&accion=editar');
		}
	}
} else if (isset($_POST['cancelar'])) {
	unset($_SESSION['msgError']);
	unset($_SESSION['modExperto']);
	header('Location: ./index.php?page=expertos');
}

echo "<div class=\"container\">
		<p><span class=\"glyphicon glyphicon-user\"></span> Conectado al sistema como: ".$_SESSION['usuario'][0]['usuario']."</p>
	</div>";

if (!isset($_GET['accion'])) {
	echo "<div class=\"container\">
			<label>Buscador</label>
			<p><input type=\"text\" class=\"form form-control\" onkeyup=\"showHint(this.value)\" placeholder=\"Búsqueda\">
			<a href=\"./index.php?page=expertos&accion=annadir\" class=\"btn btn-success\"><span class=\"glyphicon glyphicon-user\"></span> Añadir experto</a></p>
		</div>";
	echo "<div class=\"container\">";
	echo "<h3>Expertos</h3>";
	$experto = new ModelExperto();
	$resultado = $experto->getExpertos();
	/*if (isset($_POST['buscar'])) {
		$texto = limpiarCadena($_POST['search']);
		$resultado = $experto->buscarExpertos($texto);
	} else {
		$resultado = $experto->getExpertos();
	}*/
	$experto = null;
	if (!empty($resultado)) {
		echo "<div id=\"txtHint\">";
		echo "<table class=\"table table-hover table-striped\">
				<th class=\"text-center\">Nombre</th><th class=\"text-center\">Usuario</th><th class=\"text-center\">Email</th><th class=\"text-center\">Categorias</th><th colspan=\"3\" class=\"text-center\">Opciones</th>";
		foreach ($resultado as $key => $value) {
			echo "<tr>";
			echo "<td>".$value['nombre']."</td><td>".$value['usuario']."</td><td>".$value['email']."</td>
				  <td><ul class=\"nav\">";
				  foreach ($value['categorias'] as $key => $value1) {
				  	echo "<li>".$value1."</li>";
				  }
			echo "</ul></td>";
			echo "<td><a href=\"./index.php?page=expertos&usuario=".$value['usuario']."&accion=editar\" class=\"btn btn-primary\"><span class=\"glyphicon glyphicon-pencil\"></span> Editar</a> <a href=\"./index.php?page=expertos&usuario=".$value['usuario']."&accion=eliminar\" class=\"btn btn-danger\"><span class=\"glyphicon glyphicon-remove\"></span> Eliminar</a></td>";
			echo "</tr>";
		}
		echo "</table>";
		echo "</div>";
	}
}

if (isset($_GET['accion']) && ($_GET['accion'] == "annadir" || $_GET['accion'] == "editar")) {
	echo "<form method=\"post\" action=\"" . htmlspecialchars('./index.php?page=expertos&accion=annadir') . "\">";
	echo "<div class=\"container\">";
	echo "<h3>" . $accion . "</h3>";
	echo "<p>
			<label>Nombre</label>
			<input type=\"text\" class=\"form form-control\" name=\"nombre\" value=\"".$nombre."\" placeholder=\"Nombre\">
			<span class=\"error\">".$nombreError."</span>
		  </p>";
	echo "<p>
			<label>Usuario</label>
			<input type=\"text\" class=\"form form-control\" name=\"usuario\" value=\"".$usuario."\" placeholder=\"Usuario\">
			<span class=\"error\">".$usuarioError."</span>
		  </p>";
	echo "<p>
			<label>Password</label>
			<input type=\"password\" class=\"form form-control\" name=\"password\" value=\"".$password."\" placeholder=\"Password\">
			<span class=\"error\">".$passwordError."</span>
		  </p>";
	echo "<p>
			<label>Re-Password</label>
			<input type=\"password\" class=\"form form-control\" name=\"passwordR\" value=\"".$password."\" placeholder=\"Re-Password\">
			<span class=\"error\">".$passwordRError."</span>
		  </p>";
	echo "<p>
			<label>Email</label>
			<input type=\"text\" class=\"form form-control\" name=\"email\" value=\"".$email."\" placeholder=\"Email\">
			<span class=\"error\">".$emailError."</span>
		  </p>";
	$categoria = new ModelCategoria();
	$listaCategorias = $categoria->getCategorias();
	$categoria = null;
	echo "<label>Categorías</label>";
	$categoriaExperto = new ModelExperto();
	$listaCatExp = $categoriaExperto->getCategoriaExperto($id);
	$categoriaExperto = null;
	$contador = 0;
	$catExistente = false;
	foreach ($listaCategorias as $key => $value) {
		if ($contador == 5) {
			echo "<br />";
			$contador = 0;
		}
		if (!empty($listaCatExp)) {
			foreach ($listaCatExp as $key1 => $value1) {
				if (in_array($value['categoria'], $listaCatExp[$key1])) {
					$catExistente = true;
					break;
				}
			}
		}
		if ($catExistente) {
			$catExistente = false;
			echo "<input type=\"checkbox\" name=\"categorias[]\" value=\"".$value['categoria']."\" checked>".$value['categoria']."	";
		} else {
			echo "<input type=\"checkbox\" name=\"categorias[]\" value=\"".$value['categoria']."\">".$value['categoria']."	";
		}
		$contador++;
	}
	echo "<p class=\"error\">".$categoriaError."</p>";
	echo "<p>
			<input type=\"submit\" class=\"btn btn-primary\" name=\"".$btn."\" value=\"Aceptar\">
			<input type=\"submit\" class=\"btn btn-danger\" name=\"cancelar\" value=\"Cancelar\">
		</p>";
	echo "</form>";
	echo "<p class=\"error\">".$msgError."</p>";
	echo "</div>";
}
echo "</div>";