<?php
/**
 * inc/codigo_inicializacion.php (basado en la API MySQLi Orientado a Objetos)
 * Script crítico de arranque y preparación del entorno del servidor.
 */

// 1. CONFIGURACIÓN DEL PROCESADOR: Activa el almacenamiento en búfer de salida HTTP.
// Evita fallos de "headers already sent" al realizar redirecciones header() posteriores.
ob_start(); 
	
// 2. DEFINICIÓN DE CONSTANTES GLOBALES DEL SISTEMA
define("BAD_REQUEST", -2);
define("CANCELAR", -1);
define("OK", 0);
define("ERROR_ALTA_PERSONA", 1);
define("ERROR_MODIFICA_PERSONA", 2);
define("ERROR_BORRADO_PERSONA", 3);
define("NO_EXISTE_PERSONA", 4);
	
// 3. CONEXIÓN CON LA BASE DE DATOS (API MySQLi Orientada a Objetos)
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
