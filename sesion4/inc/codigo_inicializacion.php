<?php
/**
 * inc/codigo_inicializacion.php (basado en la API MySQLi Orientado a Objetos)
 * Script crítico de arranque y preparación del entorno del servidor.
 */

// 1. CONFIGURACIÓN DEL PROCESADOR: Activa el almacenamiento en búfer de salida HTTP.
// Evita fallos de "headers already sent" al realizar redirecciones header() posteriores.
ob_start(); 
	
// 2. RAÍZ FÍSICA DEL SISTEMA DE ARCHIVOS (Para uso en require/include)
// Como este archivo se encuentra en "inc/", se sube un nivel (con dirname()) para encontrar la raíz real del 
// En este despliegue concreto, "/home/fdiaz/public_html/lab2026/sesion4/"
define("RAIZ_FISICA", dirname(__DIR__) . "/");

// 3. RAÍZ DINÁMICA DE URL DE NAVEGACIÓN (Para uso en header() y HTML: href, src, action)
// Se calcula diseccionando la URL que está ejecutando el navegador actual.
$currentScript = $_SERVER['SCRIPT_NAME']; 
// Ejemplos reales de ejecución:
// - Si se ejecuta desde el index.php de la raíz:        /~fdiaz/lab2026/sesion4/index.php
// - Si se ejecuta desde el controlador de personas:    /~fdiaz/lab2026/sesion4/src/controladores/persona.php
// OJO!!! ÚNICAMENTE EL CONTROLADOR FRONTAL (index.php) y LOS CONTROLADORES DE ACCIONES (todos bajo src/controladores/)
// DEBEN TENER UNA URL ASOCIADA

// Buscamos si la petición se está procesando desde dentro de la estructura profunda de módulos
$posicionControladores = strpos($currentScript, '/src/controladores/');

if ($posicionControladores !== false) {
    // Si el script está dentro de un controlador, nos quedamos con todo el texto que haya ANTES de '/src/controladores/'
    $baseUrl = substr($currentScript, 0, $posicionControladores); //en este caso, "/~fdiaz/lab2026/sesion4/"
} else {
    // Si se ejecuta desde la raíz del proyecto (ej: index.php), limpiamos el nombre del script del final
    $baseUrl = str_replace('/index.php', '', $currentScript); //para quedarnos también con "/~fdiaz/lab2026/sesion4/"
}

// Limpiamos barras duplicadas por seguridad y fijamos la constante con una barra de cierre limpia.
$baseUrl = '/' . ltrim($baseUrl, '/');
define("RAIZ_URL", rtrim($baseUrl, '/') . '/');
// El resultado para el navegador sería EXACTAMENTE: "/~fdiaz/lab2026/sesion4/", QUE HABRÁ QUE CONCATENAR, EN 
// FORMA DE PREFIJO A TODA URL UTILIZADA EN LA APP

// 4. DEFINICIÓN DE CONSTANTES GLOBALES DEL SISTEMA
define("BAD_REQUEST", -2);
define("CANCELAR", -1);
define("OK", 0);
define("ERROR_ALTA_PERSONA", 1);
define("ERROR_MODIFICA_PERSONA", 2);
define("ERROR_BORRADO_PERSONA", 3);
define("NO_EXISTE_PERSONA", 4);
	
// 5. CONEXIÓN CON LA BASE DE DATOS (API MySQLi Orientada a Objetos)
$host = "localhost";
$usuario = "tweb";
$password = "euldlm16";
$nombreBD = "tweb";
	
// Se instancia el objeto de conexión pasándole directamente la base de datos
$conexionBD = new mysqli($host, $usuario, $password, $nombreBD);

// Se comprueba si ha ocurrido algún error crítico en la conexión
if ($conexionBD->connect_error) {
	// Nota de seguridad: En producción es mejor registrar el error internamente y no mostrarlo al usuario.
	die("Error crítico: No se pudo conectar a la base de datos: " . $conexionBD->connect_error);
}

// Se fuerza a utilizar el juego de caracteres a UTF-8 para evitar problemas con tildes y eñes
$conexionBD->set_charset("utf8mb4");

?>
