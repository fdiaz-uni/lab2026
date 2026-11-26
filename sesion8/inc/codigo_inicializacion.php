<?php
/**
 * inc/codigo_inicializacion.php (basado en la API MySQLi Orientado a Objetos)
 * Script crítico de arranque y preparación del entorno del servidor.
 */

// 1. ARRANQUE del sistema de sesiones nativo de PHP (comprobando previamente que no esté activa)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. RAÍZ FÍSICA DEL SISTEMA DE ARCHIVOS (Para uso en require/include)
// Como este archivo se encuentra en "inc/", se sube un nivel (con dirname()) para encontrar la raíz real del 
// En este despliegue concreto, "/home/fdiaz/public_html/lab2026/sesion4/"
define("RAIZ_FISICA", dirname(__DIR__) . "/");

// 3. DEFINICIÓN DE CONSTANTES GLOBALES DEL SISTEMA
define("OK", 0);						// 200 OK
define("ERROR_ALTA_PERSONA", 1);		// 409 CONFLICT
define("ERROR_MODIFICA_PERSONA", 2);	// 409 CONFLICT
define("ERROR_BORRADO_PERSONA", 3);		// 500 INTERNAL SERVER ERROR
define("NO_EXISTE_PERSONA", 4);			// 404 NOT FOUND
define("ERROR_CONSULTA_PERSONA", 5);	// 500 INTERNAL SERVER ERROR

	
// 4. CONEXIÓN CON LA BASE DE DATOS (API MySQLi Orientada a Objetos)
$host = "localhost";
$usuario = "tweb";
$password = "euldlm16";
$nombreBD = "twebV2";
	
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
