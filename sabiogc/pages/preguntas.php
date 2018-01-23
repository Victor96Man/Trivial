<?php
if ($_SESSION['perfil'] != "experto" && $_SESSION['perfil'] != "admin") {
	header("Location: ./index.php");
}

require "./funciones/upload.php";
require "./includes/ModelPregunta.php";
require "./includes/ModelCategoria.php";
require "./includes/ModelExperto.php";
require "./funciones/limpiarCadena.php";
?>
<script src='./js/searchPreg.js'></script>
<script src='./js/vendor/jquery-1.11.2.min.js'></script>
<script src='./js/comprobarObjeto.js'></script>

<?php

$errorP = $errorR1 = $errorR2 = $errorR3 = $errorR4 = $errorV = $errorC = $errorN = false;
$pregunta = $respuesta1 = $respuesta2 = $respuesta3 = $respuesta4 = $valida = $categoria = $nivel = $ruta = $objeto = $tipo = "";
$preguntaError = $respuesta1Error = $respuesta2Error = $respuesta3Error = $respuesta4Error = $validaError = $categoriaError = $nivelError = $msgError = "";

if (isset($_GET['id'])) {
	$_SESSION['modPregunta'] = $_GET['id'];
}

if (isset($_SESSION['msgError'])) {
	$msgError = $_SESSION['msgError'];
}
//ACCION
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
?>

<div class='container'>
	<p><span class='glyphicon glyphicon-user'></span> Conectado al sistema como: <?= $_SESSION['usuario'][0]['usuario'] ?> </p>
</div>

<?php
if (!isset($_GET['accion'])) {?>
	<div class="container">
			<label>Buscador</label>
			<p><input type='text' class='form form-control' onkeyup='showHint(this.value)' placeholder='Búsqueda'>
	<?php if ($_SESSION['perfil'] == "experto") { ?>
		<a href="./index.php?page=preguntas&accion=annadir" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Añadir pregunta</a></p>
	<?php } ?>
</div>
<div class="container">
	<h3>Preguntas</h3>
	<?php
	$preguntas = new ModelPregunta();
	if ($_SESSION['perfil'] == "experto") {
		$resultado = $preguntas->getPreguntas($_SESSION['usuario'][0]['id'], 5);
		?>
		<p>Últimas 5 preguntas</p>
	<?php } else if ($_SESSION['perfil'] == "admin") {
		$resultado = $preguntas->getPreguntasAdmin();
		?>
		<p>Últimas 15 preguntas</p>
	<?php }
	/*if (isset($_POST['buscar'])) {
		if (isset($_POST['search'])) {
			$texto = limpiarCadena($_POST['search']);
		} else {
			$texto = "
		}
		if ($_SESSION['perfil'] == "experto") {
			$resultado = $preguntas->buscarPreguntas($_SESSION['usuario'][0]['id'], $texto);
		} else if ($_SESSION['perfil'] == "admin") {
			$resultado = $preguntas->buscarPreguntasAdmin($texto);
		}
	} else {
		if ($_SESSION['perfil'] == "experto") {
			$resultado = $preguntas->getPreguntas($_SESSION['usuario'][0]['id'], 5);
			<p>Últimas 5 preguntas</p>
		} else if ($_SESSION['perfil'] == "admin") {
			$resultado = $preguntas->getPreguntasAdmin();
			<p>Últimas 15 preguntas</p>
		}
	}*/
	$preguntas = null;
	if (!empty($resultado)) {?>
		<div id='txtHint'>
			<table class='table table-hover table-striped'>
				<th class='text-center'>Pregunta</th><th class='text-center'>Categoría</th><th class='text-center'>Nivel</th><th colspan='3' class='text-center'>Opciones</th>
			<?php foreach ($resultado as $key => $value) { ?>
				<tr>
					<td><?= $value['pregunta'] ?></td><td><?= $value['categoria'] ?></td><td><?= $value['nivel'] ?></td>
					<td><a href=<?= './index.php?page=preguntas&id='.$value['id'].'&accion=editar'?> class='btn btn-primary'><span class='glyphicon glyphicon-pencil'></span> Editar</a></td>
				</tr>
			<?php } ?>
			</table>
		</div>
	<?php } else { ?>
 		<br/>
 		<p>Aún no has introducido preguntas.</p>
 		<br/>
	<?php } ?>
	</div>
<?php } 

if (isset($_GET['accion']) && ($_GET['accion'] == "annadir" || $_GET['accion'] == "editar")) { ?>
<div class='container'>
	<form method='post' action= <?= htmlspecialchars('./index.php?page=preguntas&accion=annadir') ?> enctype='multipart/form-data'>
	<h3><?= $accion ?></h3>
	<p>
		<label>Pregunta</label>
		<input type='text' class='form preg form-control' name='pregunta' value=<?= $pregunta ?>>
		<span class='error'><?= $preguntaError ?></span>
	</p>
	<?php
	if ($_SESSION['perfil'] == "experto") {
		$objCategoria = new ModelExperto();
		$resultadoCat = $objCategoria->getCategoriaExperto($_SESSION['usuario'][0]['id']);
	} else if ($_SESSION['perfil'] == "admin") {
		$objCategoria = new ModelCategoria();
		$resultadoCat = $objCategoria->getCategorias();
	}
	$objCategoria = null;
	?>
	<p>
		<label>Categoria</label>
		<select name='categoria'>
			<?php foreach ($resultadoCat as $key => $value) {
				if ($value['categoria'] == $categoria) { ?>
					<option value=<?= $value['categoria'] ?> selected> <?= $value['categoria'] ?> ></option>   
				<?php }else{ ?>
					<option value=<?= $value['categoria'] ?>> <?= $value['categoria']?> ></option>     
				<?php }
			} ?>
		</select>
		<span class='error'><?= $categoriaError ?></span>
	<?php
	$objNivel = new ModelPregunta();
	$resultadoNivel = $objNivel->getNivel();
	$objNivel = null;
	?>
	<label id='dificultad'>Dificultad</label>
		<select name='dificultad'>
		<?php foreach ($resultadoNivel as $key => $value) {
			if ($value['nivel'] == $nivel) { ?>
				<option value=<?= $value['nivel']?> selected><?= $value['nivel']?></option>
			<?php } else { ?>
				<option value=<?= $value['nivel']?>><?= $value['nivel']?></option>
			<?php }
		} ?>
	</select>
	<span class='error'><?= $nivelError ?></span>
	</p>
	<div class='row'>
		<div class='col-md-6'>
		<p>
		<label>Respuesta 1</label>
		<input type='text' class='form resp form-control' name='respuesta1' value=<?= $respuesta1 ?>>
		<span class='error'><?= $respuesta1Error ?></span>
	</p>
	<p>
		<label>Respuesta 2</label>
		<input type='text' class='form resp form-control' name='respuesta2' value=<?= $respuesta2 ?>>
		<span class='error'><?= $respuesta2Error ?></span>
	</p>
	<p>
		<label>Respuesta 3</label>
		<input type='text' class='form resp form-control' name='respuesta3' value=<?= $respuesta3 ?>>
		<span class='error'><?= $respuesta3Error ?></span>
	</p>
	<p>
		<label>Respuesta 4</label>
		<input type='text' class='form resp form-control' name='respuesta4' value=<?= $respuesta4 ?>>
		<span class='error'><?= $respuesta4Error ?></span>
	</p>
	<p>
		<label>Respuesta válida</label>
		<select name='valida'>
		<?php for ($i = 1; $i < 5 ; $i++) { 
			if ($i == $valida) { ?>
				<option value=<?= $i ?> selected><?= $i ?></option>
			<?php }else{ ?>
				<option value=<?= $i ?>><?= $i?></option>
			<?php } 
		} ?>
		</select>
		<span class='error'><?= $validaError?></span>
	</p>
</div>



<?php if ($_GET['accion'] == "editar" && $_SESSION['tipoObjeto'] != "") { ?>
	<div class='col-md-3'>
		<?php switch ($_SESSION['tipoObjeto']) {
			case 'Imagen': ?>
				<img src=<?='./uploads/imagenes/'.$_SESSION['objeto'] ?> width='150px' height='150px'>
				<?php break;
			case 'Vídeo': ?>
				<video src=<?= './uploads/videos/'.$_SESSION['objeto'] ?> controls width='320px' height='215px'></video>
				<?php break;
			case 'Audio':?>
				<audio controls>
					<source src=<?= './uploads/audios/'.$_SESSION['objeto'] ?>> 
				</audio>
				<?php break;
			case 'Link': ?>
				<iframe width='320' height='215' src=<?= str_replace("watch?v=", "embed/", $_SESSION['objeto']).'?html5=1?'?> frameborder='0' allowfullscreen">
					
				</iframe>
				<?php break;
		} ?>
		<p>
			<input type='checkbox' name='delObjeto' value='1'> Eliminar objeto
		</p>
	</div>
	<?php } ?>
</div>
	<p>
		<label>Objeto a subir</label>
		<input type="radio" name="obj" value="archivo" checked>Subir archivo
		<input type="radio" name="obj" value="url" style="margin-left: 1em;">Subir url
	</p>
	<p id="fichero" style="display: none;">
		<input type="file" name="upload">
	</p>
	<p id="ficheroURL" style="display: none;">
		<label style="vertical-align: top">URL</label>
		<textarea class="form url form-control" rows="3" name="url"></textarea>
	</p><br/>
	<?php if ($_GET['accion'] == "annadir") { ?>
		<p>
			<input type="submit" class="btn btn-primary" name="enviar" value="Aceptar">
			<input type="submit" class="btn btn-danger" name="cancelar" value="Cancelar">
		</p>
	<?php } else if ($_GET['accion'] == "editar") { ?>
		<p>
			<input type="submit" name="editar" value="Editar" class="btn btn-primary">
			<input type="submit" name="eliminar" value="Eliminar" class="btn btn-danger">
			<input type="submit" name="cancelar" value="Cancelar" class="btn btn-danger">
		</p>
	<?php } ?>
		<p class="error"><?= $msgError?></p>
	</form>
</div>
<?php }