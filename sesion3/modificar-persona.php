<?php 
// 1. Se inicializa el entorno del servidor y buffers HTTP
include_once "inc/codigo_inicializacion.php"; 

// 2. Requerimientos de dependencias necesarios para generar el HTML de la página
require_once "inc/plantilla.php";
require_once "inc/modelo-persona.php";

// 3. Definición de título y arranque semántico de la cabecera
define("TITULO_PAGINA", "Modificar persona");
cabeceraPlantilla('personas'); // Mantiene activa la sección de personas en el menú
?>

<div class="container">
    <h1>Personas</h1>
    
    <!-- 4. Inclusión del componente con el formulario compartido -->
    <?php include_once "inc/componente-persona.php"; ?>
    
</div> <!-- /.container -->

<?php 
// 5. Invocación de la estructura del pie de página
piePlantilla();

// 6. Cierre seguro de las conexiones del servidor
include_once "inc/codigo_finalizacion.php"; 
?>
