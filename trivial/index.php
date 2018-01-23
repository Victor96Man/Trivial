<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.1/js/materialize.min.js"></script>
	<link href='https://fonts.googleapis.com/css?family=Roboto:400' rel='stylesheet' type='text/css'>
	<script type="text/javascript" src="./js/formulario.js"></script>
	<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/css/materialize.min.css">-->
	<link rel=stylesheet href="css/portada.css" type="text/css"/>
	<title>Configuracion</title>
</head>
<body>
	<div id="formulario" class="card">
		<h2>Una pregunta en la Mochila</h2>
		<form action="juego.php" method="post">
				
				<div class="fila">
					<input type="text" name="rondas" id="rondas" placeholder="Nº- Rondas"><span id="rondaS"></span>
					<select name="numJugadores" id="numJugadores">
									<option value="">Nº- jugadores</option>
						<?php
							for ($i=1; $i <4 ; $i++) { 
								echo "<option value=".($i+1).">".($i+1)."</option>";
							}
						?>
					</select>
				</div>
				<div id="jugadores">
					
				</div>
				<input  type="button" value="Jugar" id="jugar" name="jugar">
			
		</form>
	</div>
	
</body>
</html>



