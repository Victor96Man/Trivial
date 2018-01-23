<?php
require_once "./funciones/limpiarCadena.php";

if (empty($_POST['nombre'])) {
	$nombreError = "Nombre no válido.";
	$errorN = true;
} else {
	$nombre = limpiarCadena($_POST['nombre']);
	/*if (!preg_match("/^[a-zA-ZáéíóúñÁÉÍÓÚÑ]+$/",$nombre)) {
		$nombreError = "Formato de nombre no válido.";
		$errorN = true;
	}*/
}

if (empty($_POST['usuario'])) {
	$usuarioError = "Usuario no válido.";
	$errorU = true;
} else {
	$usuario = limpiarCadena($_POST['usuario']);
	/*if (!preg_match("/^[a-zA-Z0-9]+$/",$usuario)) {
		$usuarioError = "Formato de usuario no válido.";
		$errorU = true;
	}*/
}

if (empty($_POST['password'])) {
	$passwordError = "Contraseña no válida.";
	$errorP = true;
} else {
	$password = limpiarCadena($_POST['password']);
	/*if (!preg_match("/^[a-zA-Z0-9]+$/",$password)) {
		$passwordError = "Formato de contraseña no válido.";
		$errorP = true;
	}*/
}

if (empty($_POST['passwordR'])) {
	$passwordRError = "Contraseña no válida.";
	$errorPR = true;
} else {
	$passwordR = limpiarCadena($_POST['passwordR']);
	/*if (!preg_match("/^[a-zA-Z0-9]+$/",$passwordR)) {
		$passwordRError = "Formato de contraseña no válido.";
		$errorPR = true;
	}*/
}

if ($password != $passwordR) {
	$passwordRError = "La contraseña no coincide.";
	$errorPR = true;
}

if (empty($_POST['email'])) {
	$emailError = "Email no válido.";
	$errorE = true;
} else {
	$email = limpiarCadena($_POST['email']);
	if (!preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/",$email)) {
		$emailError = "Formato de email no válido.";
		$errorE = true;
	}
}

foreach ($_POST['categorias'] as $value) {
	array_push($categorias, $value);
}

/*if (empty($_POST['categorias'])) {
	$categoriaError = "Categoría no válida.";
	$errorC = true;
} else {
	foreach ($_POST['categorias'] as $value) {
		array_push($categorias, $value);
	}
}*/