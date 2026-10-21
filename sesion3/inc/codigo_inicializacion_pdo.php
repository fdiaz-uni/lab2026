<?php
/**
 * inc/codigo_inicializacion.php (basado en la API PDO)
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

// 3. CONEXIÓN CON LA BASE DE DATOS (API PDO)
// Datos de conexión
$host = "localhost";
$usuario = "tweb";
$password = "euldlm16";
$nombreBD = "tweb";
try {
	// Crear una nueva conexión PDO
	$conexionBD = new PDO("mysql:host=$host;dbname=$nombreBD;charset=utf8", $usuario, $password);
	// Configurar el modo de error de PDO para lanzar excepciones
	$conexionBD->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	die("No se pudo conectar a la base de datos: " . $e->getMessage());
}

