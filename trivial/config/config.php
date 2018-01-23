<?php
include("./class/Preguntas.php");

$esqueletoXML = simplexml_load_file("./config/config.xml");

$rondas = $esqueletoXML->config->rondas;
$players = $esqueletoXML->config->jugadores;
$numeroRespuestas = $esqueletoXML->config->nrespuestas;
$ficheros = $esqueletoXML->config->ficheros;
$rutaImagen = $ficheros->imagen;
$rutaAudio = $ficheros->audio;
$rutaVideo = $ficheros->video;

define('RONDAS', $_POST['rondas']);
define('PLAYERS', $_POST['numJugadores']);
define('NRESPUESTAS',$numeroRespuestas);

$rutas = array("$rutaImagen","$rutaAudio","$rutaVideo");
$jugadores=array();

// foreach ($esqueletoXML->jugadores->jugador as $value) {
// 	array_push($jugadores,array("Nombre"=>$value->nombre,"Imagen"=>$value->foto));
// }
foreach ($_POST['jugadores'] as $key => $value) {
	array_push($jugadores,array("Nombre"=>$value,"Imagen"=>$_POST['foto'][$key]));
}

$pregunta = new Preguntas();
$listaCategorias = array();
$arrayPreguntas=array();


foreach ($esqueletoXML->config->categorias->categoria as  $value) {
	array_push($listaCategorias,$value);
}



$opciones = $pregunta->generarCategoriasPreguntas($listaCategorias,$arrayPreguntas);
if (empty($listaCategorias)) {
	$listaCategorias = $opciones[0];
	$arrayPreguntas = $opciones[1];
}else{
	$arrayPreguntas = $opciones;
}
$preguntas=null;


?>