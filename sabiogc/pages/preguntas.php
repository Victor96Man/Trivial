<?php
if ($_SESSION['perfil'] != "experto" && $_SESSION['perfil'] != "admin") {
	header("Location: ./index.php");
}

require_once "./funciones/upload.php";
require_once "./includes/ModelPregunta.php";
require_once "./includes/ModelCategoria.php";
require_once "./includes/ModelExperto.php";
require_once "./funciones/limpiarCadena.php";

echo "<script src=\"./js/searchPreg.js\"></script>";
echo "<script src=\"./js/vendor/jquery-1.11.2.min.js\"></script>";
echo "<script src=\"./js/comprobarObjeto.js\"></script>";

$errorP = $errorR1 = $errorR2 = $errorR3 = $errorR4 = $errorV = $errorC = $errorN = false;
$pregunta = $respuesta1 = $respuesta2 = $respuesta3 = $respuesta4 = $valida = $categoria = $nivel = $ruta = $objeto = $tipo = "";
$preguntaError = $respuesta1Error = $respuesta2Error = $respuesta3Error = $respuesta4Error = $validaError = $categoriaError = $nivelError = $msgError = "";

if (isset($_GET['id'])) {
	$_SESSION['modPregunta'] = $_GET['id'];
}

if (isset($_SESSION['msgError'])) {
	$msgError = $_SESSION['msgError'];
}

if (isset($_GET['accion'])) {
	if ($_GET['accion'] == "editar") {
		$_SESSION['oldRespuestas'] = array();
		$objPregunta = new ModelPregunta();
		$resultadoPreg = $objPregunta->getPregunta($_SESSION['modPregunta']);
		$objPregunta = null;
		$pregunta = $resultadoPreg[0]['pregunta'];
		$_SESSION['oldPregunta'] = $pregunta;
		$categoria = $resultadoPreg[0]['categoria'];
		$nivel = $resultadoPreg[0]['nivel'];
		$_SESSION['objeto'] = $resultadoPreg[0]['Objeto'];
		$_SESSION['tipoObjeto'] = $resultadoPreg[0]['tipoObjeto'];
		$objPregunta = new ModelPregunta();
		$resultadoResp = $objPregunta->getRespuestas($_SESSION['modPregunta']);
		$contador = 1;
		foreach ($resultadoResp as $key => $value) {
			switch ($contador) {
				case 1:
					$respuesta1 = $value['respuesta'];
					array_push($_SESSION['oldRespuestas'],$respuesta1);
					break;
				case 2:
					$respuesta2 = $value['respuesta'];
					array_push($_SESSION['oldRespuestas'],$respuesta2);
					break;
				case 3:
					$respuesta3 = $value['respuesta'];
					array_push($_SESSION['oldRespuestas'],$respuesta3);
					break;
				case 4:
					$respuesta4 = $value['respuesta'];
					array_push($_SESSION['oldRespuestas'],$respuesta4);
					break;
			}
			if ($value['valida'] == 1) {
				$valida = $contador;
			}
			$contador += 1;
		}
		$accion = "Editar pregunta";
		$objPregunta = null;
	} else if ($_GET['accion'] == "annadir") {
		$accion = "Añadir pregunta";
	}
}

if (isset($_POST['enviar'])) {
	include("./funciones/validarPreguntas.php");
	if (!$errorP && !$errorR1 && !$errorR2 && !$errorR3 && !$errorR4 && !$errorV && !$errorC && !$errorN) {
		$respuestas = array($respuesta1, $respuesta2, $respuesta3, $respuesta4);
		$preguntas = new ModelPregunta();
		$preguntas->insPregunta(array('pregunta'=>$pregunta,'valida'=>$valida,'categoria'=>$categoria,'nivel'=>$nivel,'idExperto'=>$_SESSION['usuario'][0]['id']),$respuestas);
		$preguntas = null;
		header('Location: ./index.php?page=preguntas');
	} else {
		$_SESSION['msgError'] = "Pregunta no creada.";
	}
} else if (isset($_POST['eliminar'])) {
	$preguntas = new ModelPregunta();
	$preguntas->delPregunta($_SESSION['modPregunta']);
	$preguntas = null;
	unset($_SESSION['modPregunta']);  
	unset($_SESSION['objeto']);
	unset($_SESSION['oldPregunta']);
	unset($_SESSION['oldRespuestas']);
	unset($_SESSION['msgError']);
	header('Location: ./index.php?page=preguntas');
} else if (isset($_POST['editar'])) {
	include("./funciones/validarPreguntas.php");
	if (!$errorP && !$errorR1 && !$errorR2 && !$errorR3 && !$errorR4 && !$errorV && !$errorC && !$errorN) {
		$respuestas = array($respuesta1, $respuesta2, $respuesta3, $respuesta4);
		$preguntas = new ModelPregunta();
		if (isset($_POST['url']) && !empty($_POST['url'])) {
			$preguntas->updPregunta($_SESSION['oldPregunta'], $_SESSION['oldRespuestas'], $_SESSION['objeto'], $_SESSION['tipoObjeto'], $_POST['delObjeto'], array('pregunta'=>$pregunta,'objeto'=>$_POST['url'],'tipoObjeto'=>"",'categoria'=>$categoria,'nivel'=>$nivel,'idExperto'=>$_SESSION['usuario'][0]['id'],'respuestas'=>$respuestas,'valida'=>$valida));
		} else {
			$preguntas->updPregunta($_SESSION['oldPregunta'], $_SESSION['oldRespuestas'], $_SESSION['objeto'], $_SESSION['tipoObjeto'], $_POST['delObjeto'], array('pregunta'=>$pregunta,'objeto'=>$_FILES['upload']['name'],'tipoObjeto'=>"",'categoria'=>$categoria,'nivel'=>$nivel,'idExperto'=>$_SESSION['usuario'][0]['id'],'respuestas'=>$respuestas,'valida'=>$valida));
		}
		$preguntas = null;
		unset($_SESSION['modPregunta']);  
		unset($_SESSION['objeto']);
		unset($_SESSION['tipoObjeto']);
		unset($_SESSION['oldPregunta']);
		unset($_SESSION['oldRespuestas']);
		unset($_SESSION['msgError']);
		header('Location: ./index.php?page=preguntas');
	} else {
		$_SESSION['msgError'] = "Pregunta no modificada.";
		header('Location: ./index.php?page=preguntas&modalidad=editar&id='.$_SESSION['modPregunta']);
	}
} else if (isset($_POST['cancelar'])) {
	unset($_SESSION['modPregunta']);  
	unset($_SESSION['objeto']);
	unset($_SESSION['tipoObjeto']);
	unset($_SESSION['oldPregunta']);
	unset($_SESSION['oldRespuestas']);
	unset($_SESSION['msgError']);
	header('Location: ./index.php?page=preguntas');
}

echo "<div class=\"container\">
		<p><span class=\"glyphicon glyphicon-user\"></span> Conectado al sistema como: ".$_SESSION['usuario'][0]['usuario']."</p>
	</div>";

if (!isset($_GET['accion'])) {
	echo "<div class=\"container\">
			<label>Buscador</label>
			<p><input type=\"text\" class=\"form form-control\" onkeyup=\"showHint(this.value)\" placeholder=\"Búsqueda\">";
	if ($_SESSION['perfil'] == "experto") {
		echo "<a href=\"./index.php?page=preguntas&accion=annadir\" class=\"btn btn-success\"><span class=\"glyphicon glyphicon-plus\"></span> Añadir pregunta</a></p>";
	}
	echo "</div>";
	echo "<div class=\"container\">";
	echo "<h3>Preguntas</h3>";
	$preguntas = new ModelPregunta();
	if ($_SESSION['perfil'] == "experto") {
		$resultado = $preguntas->getPreguntas($_SESSION['usuario'][0]['id'], 5);
		echo "<p>Últimas 5 preguntas</p>";
	} else if ($_SESSION['perfil'] == "admin") {
		$resultado = $preguntas->getPreguntasAdmin();
		echo "<p>Últimas 15 preguntas</p>";
	}
	/*if (isset($_POST['buscar'])) {
		if (isset($_POST['search'])) {
			$texto = limpiarCadena($_POST['search']);
		} else {
			$texto = "";
		}
		if ($_SESSION['perfil'] == "experto") {
			$resultado = $preguntas->buscarPreguntas($_SESSION['usuario'][0]['id'], $texto);
		} else if ($_SESSION['perfil'] == "admin") {
			$resultado = $preguntas->buscarPreguntasAdmin($texto);
		}
	} else {
		if ($_SESSION['perfil'] == "experto") {
			$resultado = $preguntas->getPreguntas($_SESSION['usuario'][0]['id'], 5);
			echo "<p>Últimas 5 preguntas</p>";
		} else if ($_SESSION['perfil'] == "admin") {
			$resultado = $preguntas->getPreguntasAdmin();
			echo "<p>Últimas 15 preguntas</p>";
		}
	}*/
	$preguntas = null;
	if (!empty($resultado)) {
		echo "<div id=\"txtHint\">";
		echo "<table class=\"table table-hover table-striped\">
				<th class=\"text-center\">Pregunta</th><th class=\"text-center\">Categoría</th><th class=\"text-center\">Nivel</th><th colspan=\"3\" class=\"text-center\">Opciones</th>";
		foreach ($resultado as $key => $value) {
			echo "<tr>";
			echo "<td>".$value['pregunta']."</td><td>".$value['categoria']."</td><td>".$value['nivel']."</td>";
			echo "<td><a href=\"./index.php?page=preguntas&id=".$value['id']."&accion=editar\" class=\"btn btn-primary\"><span class=\"glyphicon glyphicon-pencil\"></span> Editar</a></td>";
			echo "</tr>";
		}
		echo "</table>";
		echo "</div>";
	} else {
		echo "<br /><p>Aún no has introducido preguntas.</p><br />";
	}
	echo "</div>";
}

if (isset($_GET['accion']) && ($_GET['accion'] == "annadir" || $_GET['accion'] == "editar")) {
	echo "<div class=\"container\">";
	echo "<form method=\"post\" action=\"" . htmlspecialchars('./index.php?page=preguntas&accion=annadir') . "\" enctype=\"multipart/form-data\">";
	echo "<h3>" . $accion . "</h3>";
	echo "<p>
			<label>Pregunta</label>
			<input type=\"text\" class=\"form preg form-control\" name=\"pregunta\" value=\"".$pregunta."\">
			<span class=\"error\">".$preguntaError."</span>
		  </p>";
	if ($_SESSION['perfil'] == "experto") {
		$objCategoria = new ModelExperto();
		$resultadoCat = $objCategoria->getCategoriaExperto($_SESSION['usuario'][0]['id']);
	} else if ($_SESSION['perfil'] == "admin") {
		$objCategoria = new ModelCategoria();
		$resultadoCat = $objCategoria->getCategorias();
	}
	$objCategoria = null;
	echo "<p>
			<label>Categoria</label>
			<select name=\"categoria\">";
			foreach ($resultadoCat as $key => $value) {
				if ($value['categoria'] == $categoria) {
					echo "<option value=\"".$value['categoria']."\" selected>".$value['categoria']."</option>";   
				}else{
					echo "<option value=\"".$value['categoria']."\">".$value['categoria']."</option>";      
				}
			}
	echo "	</select>
			<span class=\"error\">".$categoriaError."</span>";
	$objNivel = new ModelPregunta();
	$resultadoNivel = $objNivel->getNivel();
	$objNivel = null;
	echo "	<label id=\"dificultad\">Dificultad</label>
			<select name=\"dificultad\">";
			foreach ($resultadoNivel as $key => $value) {
				if ($value['nivel'] == $nivel) {
					echo "<option value=\"".$value['nivel']."\" selected>".$value['nivel']."</option>";
				} else {
					echo "<option value=\"".$value['nivel']."\">".$value['nivel']."</option>";
				}
			}
	echo "	</select>
			<span class=\"error\">".$nivelError."</span>
		</p>";
	echo "<div class=\"row\">
			<div class=\"col-md-6\">
			<p>
			<label>Respuesta 1</label>
			<input type=\"text\" class=\"form resp form-control\" name=\"respuesta1\" value=\"".$respuesta1."\">
			<span class=\"error\">".$respuesta1Error."</span>
		  </p>";
	echo "<p>
			<label>Respuesta 2</label>
			<input type=\"text\" class=\"form resp form-control\" name=\"respuesta2\" value=\"".$respuesta2."\">
			<span class=\"error\">".$respuesta2Error."</span>
		  </p>";
	echo "<p>
			<label>Respuesta 3</label>
			<input type=\"text\" class=\"form resp form-control\" name=\"respuesta3\" value=\"".$respuesta3."\">
			<span class=\"error\">".$respuesta3Error."</span>
		  </p>";
	echo "<p>
			<label>Respuesta 4</label>
			<input type=\"text\" class=\"form resp form-control\" name=\"respuesta4\" value=\"".$respuesta4."\">
			<span class=\"error\">".$respuesta4Error."</span>
		  </p>";
	echo "<p>
			<label>Respuesta válida</label>
			<select name=\"valida\">";
			for ($i = 1; $i < 5 ; $i++) { 
				if ($i == $valida) {
					echo "<option value=\"".$i."\" selected>".$i."</option>";
				}else{
					echo "<option value=\"".$i."\">".$i."</option>";
				}
			}
	echo "	</select>
			<span class=\"error\">".$validaError."</span>
		</p>
		</div>";
	if ($_GET['accion'] == "editar" && $_SESSION['tipoObjeto'] != "") {
		echo "<div class=\"col-md-3\">";
		switch ($_SESSION['tipoObjeto']) {
			case 'Imagen':
				echo "<img src=\"./uploads/imagenes/".$_SESSION['objeto']."\" width=\"150px\" height=\"150px\">";
				break;
			case 'Vídeo':
				echo "<video src=\"./uploads/videos/".$_SESSION['objeto']."\" controls width=\"320px\" height=\"215px\"></video>";
				break;
			case 'Audio':
				echo "<audio controls>
						<source src=\"./uploads/audios/".$_SESSION['objeto']."\"> 
					  </audio>";
				break;
			case 'Link':
				echo "<iframe width=\"320\" height=\"215\" src=".str_replace("watch?v=", "embed/", $_SESSION['objeto'])."?html5=1 frameborder=\"0\" allowfullscreen></iframe>";
				break;
		}
		echo "<p>
				<input type=\"checkbox\" name=\"delObjeto\" value=\"1\"> Eliminar objeto
			  </p>
			  </div>";
	}
	echo "</div>";
	echo "<p>
			<label>Objeto a subir</label>
			<input type=\"radio\" name=\"obj\" value=\"archivo\" checked>Subir archivo
			<input type=\"radio\" name=\"obj\" value=\"url\" style=\"margin-left: 1em;\">Subir url
		  </p>";
	echo "<p id=\"fichero\" style=\"display: none;\">
			<input type=\"file\" name=\"upload\">
		  </p>";
	echo "<p id=\"ficheroURL\" style=\"display: none;\">
			<label style=\"vertical-align: top\">URL</label>
			<textarea class=\"form url form-control\" rows=\"3\" name=\"url\"></textarea>
		  </p>";
	echo "<br />";
	if ($_GET['accion'] == "annadir") {
		echo "<p>
				<input type=\"submit\" class=\"btn btn-primary\" name=\"enviar\" value=\"Aceptar\">
				<input type=\"submit\" class=\"btn btn-danger\" name=\"cancelar\" value=\"Cancelar\">
			  </p>";
	} else if ($_GET['accion'] == "editar") {
		echo "<p>
				<input type=\"submit\" name=\"editar\" value=\"Editar\" class=\"btn btn-primary\">
				<input type=\"submit\" name=\"eliminar\" value=\"Eliminar\" class=\"btn btn-danger\">
				<input type=\"submit\" name=\"cancelar\" value=\"Cancelar\" class=\"btn btn-danger\">
			  </p>";
	}
	echo "<p class=\"error\">".$msgError."</p>";
	echo "</form>";
	echo "</div>";
}