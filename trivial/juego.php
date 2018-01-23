<?php
  if (!isset($_POST['jugadores'])) {
    header('Location: ./index.php');
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/css/materialize.min.css">
  <link rel="stylesheet" href="./css/estilo.css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.1/js/materialize.min.js"></script>
  <!--<script type="text/javascript" src="./js/modal.js" ></script> -->
    <?php
  include("./config/config.php");
  include("./config/variablesjs.php");
  ?>
  <script type="text/javascript" src="./js/juego.js"></script>
  <title>Una pregunta en la mochila</title> 
</head>
<body>
  <div class="row">
    <div class="col s3">
      <div class="jugadores">
        <?php
        for ($i=0; $i <PLAYERS; $i++) { 
            echo "<div class=\"jugador card desactivado\">";
              echo"<header>";
                echo "<i class=\"medium material-icons\">".$jugadores[$i]["Imagen"]."</i>";
                echo"<span class=\"puntos\">0</span>";
              echo"</header>";
              echo"<footer class=\"card\">";
                echo"<span>".$jugadores[$i]["Nombre"]."</span>";
              echo"</footer>";
            echo"</div>";
        }
        ?>  
      </div>
    </div>
  <main>
    <div class="col s8">
      <header id="cabecera">
        <h2 class="center-align" id="ronda">Ronda 1/X</h2>
        <h3 class="center-align" id="ruleta">Categoria</h3>
      </header>
       <!-- <div id="mapa"><img src="./img/espana2.png" alt="Mapa de España"></div>-->
        <!-- Modal Trigger -->
        <!-- Modal Structure -->

        <div id="modal1" class="modal">
          <div class="modal-content">
            <h4 id="titulo"></h4>
            <div id="objeto" class="objeto"></div>
            <ul class="respuestas">
              <?php 
                for ($i = 0; $i<NRESPUESTAS;$i++){
                 echo"<a  href='' ><li name='resp'></li></a>";
                }
                ?>
            </ul>
          </div>
        </div>

        <div id="modal2" class="modal">
          <div class="modal-content">
            <div id="fin">
            <h3>¡Ganador!</h3>
              <div class="corona">
              	<img src="./img/corona6.png" alt="corona">
              </div>
              <div class="ganadores" id="ganadores">

              </div>
              <footer>
                <button class="reiniciar waves-effect waves-light btn">Volver a Jugar</button><button class="salir waves-effect waves-light btn red">Salir</button>
              </footer>
            </div>
          </div>
        </div>


        <div id="inicial" class="modal">
          <div class="modal-content">
            <div id="empezar"> 
                <input class="btn botonEmpezar" type="button" value="EMPEZAR" id="jugar">
            </div>
          </div>
        </div>
    </div>
  </main>
</div>

  <footer class="findePagina">
    <p>Desarrollado por <b><a href="https://github.com/rafaelnavarroprieto">Rafael Navarro</a></b> y <b><a href="https://github.com/infordur">Pablo Durán</a></b>.</p><button class="reiniciar waves-effect waves-light btn grey lighten-3">Reiniciar</button>
  </footer>
</body>
</html>