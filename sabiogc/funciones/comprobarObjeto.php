<?php
if (!empty($_FILES['upload']['tmp_name'])) {
	$extension = pathinfo($_FILES['upload']['name']);
	if ($extension['extension']=="png" || $extension['extension']=="jpg" || $extension['extension']=="jpeg" || $extension['extension']=="gif") {
		$tipoObjeto = "Imagen";
		$ruta = "./uploads/imagenes/";
	} else if ($extension['extension']=="mp3" || $extension['extension']=="ogg" || $extension['extension']=="wav" || $extension['extension']=="midi") {
		$tipoObjeto = "Audio";
		$ruta = "./uploads/audios/";
	} else if ($extension['extension']=="avi" || $extension['extension']=="mp4" || $extension['extension']=="mpeg" || $extension['extension']=="mpg") {
		$tipoObjeto = "Video";
		$ruta = "./uploads/videos/";
	}
	$objeto = $_FILES['upload']['name'];
} else if (isset($_POST['url']) && !empty($_POST['url'])) {
	$objeto = $_POST['url'];
	$tipoObjeto = "Link";
} else {
	$objeto = "";
	$tipoObjeto = "";
}