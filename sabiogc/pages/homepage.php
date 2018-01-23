<?php
require_once "./includes/ModelUsuario.php";
require_once "./funciones/limpiarCadena.php";

$autentificacion = ModelUsuario::singleton();

$error = false;
$msgError = "";

$usuario = $password = "";

if (isset($_POST['login'])) {

	if (empty($_POST['usuario'])) {
		$msgError = "Usuario y/o contraseña no válidos.";
		$error = true;
	} else {
		$usuario = limpiarCadena($_POST['usuario']);
		/*if (!preg_match("/^[a-zA-Z0-9]+$/",$usuario)) {
       		$msgError = "Sólo se permiten letras y números.";
       		$error = true;
     	}*/
	}

	if (empty($_POST['password'])) {
		$msgError = "Usuario y/o contraseña no válidos.";
		$error = true;
	} else {
		$password = limpiarCadena($_POST['password']);
		/*if (!preg_match("/^[a-zA-Z0-9]+$/",$password)) {
       		$msgError = "Sólo se permiten letras y números.";
       		$error = true;
     	}*/
	}

	if (!$error) {
		$resultado = $autentificacion->login($usuario, $password);
		if (!$resultado) {
			$msgError = "El usuario introducido no existe.";
			$error = true;
		} else {
			if ($resultado[0]['usuario'] == "admin") {
				$_SESSION['perfil'] = "admin";
			} else {
				$_SESSION['perfil'] = "experto";
			}
			$_SESSION['usuario'] = $resultado;
			header("Location: ./index.php?page=preguntas");
		}
	}
}

echo "<form method=\"post\" action=\"" . htmlspecialchars('./index.php?page=homepage') . "\">
	  <div class=\"login container\">
		<h3>Iniciar sesión</h3>";
		if ($error) { echo "<div class=\"alert alert-danger\" role=\"alert\">".$msgError."</div>"; }
echo "	<div class=\"form-group\">
			<label>Usuario</label>
			<input type=\"text\" class=\"form-control\" name=\"usuario\" placeholder=\"Usuario\">
		</div>
		<div class=\"form-group\">
			<label>Contraseña</label>
			<input type=\"password\" class=\"form-control\" name=\"password\" placeholder=\"Contraseña\">
		</div>
		<input type=\"submit\" class=\"btn btn-primary\" name=\"login\" value=\"Iniciar sesión\"><br />
	</div>
	</form>";