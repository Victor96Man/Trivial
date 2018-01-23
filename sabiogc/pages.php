<?php
if (!isset($_GET['page'])) {
	if ($_SESSION['perfil'] == "invitado") {
		include('./pages/homepage.php');
	} else if ($_SESSION['perfil'] == "admin" || $_SESSION['perfil'] == "experto") {
		include('./pages/preguntas.php');
	}
} else if (file_exists('./pages/' . $_GET['page'] . '.php')) {
	include('./pages/' . $_GET['page'] . '.php');
} else {
	if ($_SESSION['perfil'] == "invitado") {
		include('./pages/homepage.php');
	} else if ($_SESSION['perfil'] == "admin" || $_SESSION['perfil'] == "experto") {
		include('./pages/preguntas.php');
	}
}