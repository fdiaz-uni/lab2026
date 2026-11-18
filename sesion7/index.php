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
// Se evitan fallos de variables indefinidas y se controla la sección por defecto (por defecto 'personas' si está logueado, o 'login' si no)
$estaLogueado = isset($_SESSION['usuario']);
$seccionDefecto = $estaLogueado ? 'personas' : 'login';
$seccion = $_GET['seccion'] ?? $seccionDefecto;
$accion  = $_GET['accion'] ?? 'listar';

// 4. LÓGICA DEL ENRUTAROD PRINCIPAL (discriminación de módulos y, luego, acciones, ...)
switch ($seccion) {
	/* ========================================================================
       SECCIÓN: SESIÓN USUARIO
       ======================================================================== */
    case 'login':
		// 5.1 CONTROL SESIÓN DE USUARIO: Si está activa la sesión (de usuario), automáticamente se redirige a su agenda de contactos/personas
		if ($estaLogueado) {
			header("Location: " . RAIZ_URL . "index.php?seccion=personas", true, 303);
			exit();
		}
        define("TITULO_PAGINA", "Acceso de Usuarios");
        cabeceraPlantilla('login');
		echo "<div class=\"container\">";
        require_once RAIZ_FISICA . "src/vistas/usuario/login.php";
		echo "</div> <!-- /.container -->";
        break;
    /* ========================================================================
       SECCIÓN: PERSONAS
       ======================================================================== */
    case 'personas':
		// 5.1 CONTROL SESIÓN DE USUARIO: Si NO está activa la sesión (de usuario), automáticamente se requiere autenticación, i.e., pasar por login
		if (!$estaLogueado) {
			header("Location: " . RAIZ_URL . "index.php?seccion=login&resultado=auth-required", true, 303);
			exit();
		}
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
        break;

    /* ========================================================================
       CASO POR DEFECTO: CONTROL DE ERRORES VISUALES (Páginas No Encontradas)
       ======================================================================== */
    default:
	    define("TITULO_PAGINA", "Página no encontrada");
        cabeceraPlantilla();
        echo "<div class='container'><div class='alert alert-danger'>La sección solicitada no existe.</div></div>";
        break;
}
// 6. TERMINAR plantilla
piePlantilla();
// 7. LIMPIEZA FINAL Y VOLCADO HTTP (Cierre limpio de conexiones y volcado del búfer)
require_once RAIZ_FISICA . "inc/codigo_finalizacion.php";
?>
