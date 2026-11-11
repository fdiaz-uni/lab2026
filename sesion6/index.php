<?php
/**
 * index.php
 * FRONT CONTROLLER (ENRUTADOR) ÚNICO del proyecto.
 * Centraliza la recepción de peticiones de visualización (GET), gestiona el 
 * enrutamiento del sitio web de forma modular y parametriza las plantillas.
 */
// 1. INICIALIZACIÓN del entorno (Carga de BBDD, constantes y buffers HTTP)
require_once "inc/codigo_inicializacion.php";

// 2. INCLUSIÓN DE COMPONENTES GLOBALES (Estructura de presentación y abstracción de datos)
require_once RAIZ_FISICA . "inc/plantilla.php";
require_once RAIZ_FISICA . "src/modelos/persona.php";
// En el futuro, cuando se añadan nuevas entidades, solo habrá que ir incluyendo aquí sus modelos


// 3. CAPTURA Y VALIDACIÓN DE PARÁMETROS DE ENRUTAMIENTO (Query String)
// Evitamos fallos de variables indefinidas y controlamos el acceso por defecto
$seccion = $_GET['seccion'] ?? 'personas';  //se fija sección por defecto, personas
$accion  = $_GET['accion']  ?? 'indice';	//se fija acción por defecto, indice

// 4. LÓGICA DEL ENRUTAROD PRINCIPAL (discriminación de módulos y, luego, acciones, ...)
switch ($seccion) {

    /* ========================================================================
       SECCIÓN: PERSONAS
       ======================================================================== */
    case 'personas':
        // Configuración de los metadatos específicos para este módulo
        define("TITULO_PAGINA", "Gestión de Personas");
        
        // Renderizado del encabezado común inyectando el estado de enlace activo
        cabeceraPlantilla('personas');
        
        echo "<div class=\"container\">";
        // Enrutamiento secundario basado en la acción concreta sobre la entidad 
		switch ($accion) {
			case 'modificar':
				// El componente detecta automáticamente que es edición porque viaja el ?id=X en la URL
				include_once RAIZ_FISICA . "src/vistas/persona/formulario.php";
				break;
			// Si hubiera más vistas, se irían colocando en diferentes "case" de este switch
			default:
				// Caso por defecto ('indice'): Renderiza tanto el formulario de alta como la tabla
				include_once RAIZ_FISICA . "src/vistas/persona/formulario.php"; 
				include_once RAIZ_FISICA . "src/vistas/persona/indice.php";
				break;
        }
        
        echo "</div> <!-- /.container -->";
        
        // Renderizado del cierre de etiquetas y pie de página común
        piePlantilla();
        break;

    /* ========================================================================
       MODULO: OTRO MÓDULO
       ======================================================================== */
    case 'otra':
        define("TITULO_PAGINA", "Gestión de XXXX");
        cabeceraPlantilla('otra');
        
        echo "<div class=\"container\">";
        echo "<h1>Gestión de XXX</h1>";
        echo "<p class=\"alert alert-info\">Sección preparada para nuevas secciones o áreas de gestión</p>";
        // Aquí incluirás las sub-vistas correspondientes de la nueva entidad:
        // include_once RAIZ_FISICA . "src/vistas/entidad/componente.php";
        echo "</div>";
        
        piePlantilla();
        break;

    /* ========================================================================
       CASO POR DEFECTO: CONTROL DE ERRORES VISUALES (Páginas No Encontradas)
       ======================================================================== */
    default:
        define("TITULO_PAGINA", "Error 404 - No Encontrado");
        cabeceraPlantilla('');
        
        echo "<div class=\"container text-center mt-5\">";
        echo "  <div class=\"jumbotron\">";
        echo "      <h1 class=\"text-danger\"><span class=\"glyphicon glyphicon-exclamation-sign\"></span> Error 404</h1>";
        echo "      <p class=\"lead\">La sección solicitada [<strong>" . htmlspecialchars($seccion, ENT_QUOTES, 'UTF-8') . "</strong>] no existe en el sistema.</p>";
        echo "      <hr>";
        echo "      <p><a class=\"btn btn-primary btn-lg\" href=\"" . RAIZ_URL . "index.php?seccion=personas\" role=\"button\">Volver al Inicio</a></p>";
        echo "  </div>";
        echo "</div>";
        
        piePlantilla();
        break;
}

// 5. LIMPIEZA FINAL Y VOLCADO HTTP (Cierre limpio de conexiones y volcado del búfer)
require_once RAIZ_FISICA . "inc/codigo_finalizacion.php";
?>
