<?php
require_once "ModelExperto.php";

$experto = new ModelExperto();
$q = $_REQUEST['q'];
if ($q == "") {
	$resultado = $experto->getExpertos();
} else {
	$resultado = $experto->buscarExpertos($q);
}
$experto = null;
if (!empty($resultado)) {
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
} else {
	echo "<br /><p>No existen usuarios que contengan la palabra <b>".$q."</b>.</p><br />";
}