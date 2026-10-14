<?php 
// código inicialización, siempre necesario
include_once "inc/codigo_inicializacion.php"; 

// código plantilla y funciones del modelo de datos
require_once "inc/plantilla.php";


define("TITULO_PAGINA", "Otra página de contenido ...");
cabeceraPlantilla('otra'); //ATENCIÓN!!!, añadido parámetro a la función cabeceraPlantilla($pagina_actual = ''); ?>

	<div class="container">
		El contenido exclusivo de esta otra página, se incluiría aquí ...
	</div>

<?php 
// con esta invocación se genera el HTML que aparece al FINAL de todas las páginas (plantilla)
piePlantilla();
// código finalización, limpieza final
include_once "inc/codigo_finalizacion.php"; ?>
