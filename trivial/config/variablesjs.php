<script>
	var categorias =<?php echo json_encode($listaCategorias);?>; 
	var arrayResult=<?php echo json_encode($arrayPreguntas);?>;//array con las preguntas
	var jugadores = <?php echo json_encode(PLAYERS);?>;//total jugadores
	var nrespuestas = <?php echo json_encode(NRESPUESTAS);?>;
	var rutas = <?php echo json_encode($rutas);?>;
	var rondas = <?php echo json_encode(RONDAS);?>;
	var listaJugadores = <?php echo json_encode($jugadores)?>;
	var preguntasTotales = <?php echo json_encode(RONDAS);?>*jugadores;
</script>
