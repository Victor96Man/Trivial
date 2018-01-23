<?php
if ($_SESSION['perfil'] == "admin") {
	echo "
			<nav class=\"navbar navbar-inverse\" role=\"navigation\">
				<div class=\"container\">
					<div id=\"navbar\" class=\"navbar-collapse collapse\">
						<ul class=\"nav navbar-nav\">
							<li><a href=\"./index.php?page=preguntas\">Preguntas</a></li>
							<li><a href=\"./index.php?page=categoria\">Categorías</a></li>
							<li><a href=\"./index.php?page=expertos\">Expertos</a></li>
							<li><a href=\"./funciones/cerrarSesion.php\"><span class=\"glyphicon glyphicon-log-out\"></span> Salir</a></li>
						</ul>
					</div><!--/.navbar-collapse -->
				</div>
			</nav>";
} else if ($_SESSION['perfil'] == "experto") {
	echo "
			<nav class=\"navbar navbar-inverse\" role=\"navigation\">
				<div class=\"container\">
					<div id=\"navbar\" class=\"navbar-collapse collapse\">
						<ul class=\"nav navbar-nav\">
							<li><a href=\"./index.php?page=preguntas\">Preguntas</a></li>
							<li><a href=\"./funciones/cerrarSesion.php\"><span class=\"glyphicon glyphicon-log-out\"></span> Salir</a></li>
						</ul>
					</div><!--/.navbar-collapse -->
				</div>
			</nav>";
} else {
	echo "";
}