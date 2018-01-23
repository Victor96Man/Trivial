<?php
require_once "ModelCategoria.php";

$categoria = new ModelCategoria();
$q = $_REQUEST['q'];
if ($q == "") {
	$resultado = $categoria->getCategorias();
} else {
	$resultado = $categoria->buscarCategorias($q);
}
$categoria = null;
if (!empty($resultado)) {
	echo "<table class=\"table table-hover table-striped\">
			<th class=\"text-center\">Nombre</th><th colspan=\"2\" class=\"text-center\">Opciones</th>";
	foreach ($resultado as $key => $value) {
		echo "<tr>";
		echo "<td>".$value['categoria']."</td><td><a href=\"./index.php?page=categoria&categoria=".$value['categoria']."&accion=editar\" class=\"btn btn-primary\"><span class=\"glyphicon glyphicon-pencil\"></span> Editar</a> <a href=\"./index.php?page=categoria&categoria=".$value['categoria']."&accion=eliminar\" class=\"btn btn-danger\"><span class=\"glyphicon glyphicon-remove\"></span> Eliminar</a></td>";
		echo "</tr>";
	}
	echo "</table>";
} else {
	echo "<br /><p>No existen categorías que contengan la palabra <b>".$q."</b>.</p><br />";
}