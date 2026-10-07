<?php 
// 1. Configuración de parámetros de la página
define("TITULO_PAGINA", "Quienes somos ...");

// 2. Inclusión de la plantilla
require_once "inc/plantilla.php";

// 3. Generación de la cabecera
cabeceraPlantilla();
?>
	<!-- Contenido específico de esta página -->
	<div class="contenido-principal">
		<p>Nosotros somos un equipo ...</p>
	</div>

<?php 
// 4. Generación del pie de página
piePlantilla(); ?>