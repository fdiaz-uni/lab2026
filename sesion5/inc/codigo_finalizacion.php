<?php
/**
 * inc/codigo_finalizacion.php
 * Script de cierre limpio de recursos y liberación de búferes HTTP.
 */

// 1. CIERRE DE LA CONEXIÓN CON LA BASE DE DATOS (API MySQLi Orientada a Objetos)
// Se comprueba si el objeto de conexión existe y está activo antes de cerrarlo
if (isset($conexionBD) && $conexionBD instanceof mysqli) {
	$conexionBD->close();
}

// 2. CONFIGURACIÓN DEL PROCESADOR: Vuelca el búfer de salida HTTP hacia el cliente
// Envía definitivamente todo el HTML acumulado y deshabilita el almacenamiento en búfer.
ob_end_flush(); 
?>