<?php 
// 1. Ejecución código inicialización de cada página (habilitar sesiones, conexión BD, etc.)
require_once "inc/codigo_inicializacion.php"; 

// 2. Configuración de parámetros de la página
define("TITULO_PAGINA", "Una página de contenido ...");

// 3. Inclusión de la plantilla
require_once "inc/plantilla.php"

// 4. Generación de la cabecera 
php cabeceraPlantilla();?>

	<!-- Contenido específico de esta página -->
	<div class="contenido-principal">
		<p>El contenido exclusivo de esta página, se incluiría aquí ...</p>
	</div>

<?php 
// 5. Generación del pie de página
piePlantilla();

// 6. Codigo de finalización o limpieza (cierre sesión, cierre conexión BD, etc.)
require_once "inc/codigo_finalizacion.php";
?>
