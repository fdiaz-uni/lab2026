<?php 
// 1. Configuración de parámetros de la página
define("TITULO_PAGINA", "Contacta con nosotros...");

// 2. Inclusión de la plantilla
require_once "inc/plantilla.php";

// 3. Generación de la cabecera
cabeceraPlantilla();
?>
	<!-- Contenido específico de esta página -->

	<div class="contenido-principal">
		<p>Información de contacto: dirección, teléfono, email...</p>
	</div>

<?php 
// 4. Generación del pie de página
piePlantilla(); ?>
