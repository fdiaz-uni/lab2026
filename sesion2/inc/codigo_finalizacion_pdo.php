<?php
/**
 * inc/codigo_finalizacion.php
 * Script de cierre limpio de recursos y liberación de búferes HTTP.
 */

// 1. CIERRE DE LA CONEXIÓN CON LA BASE DE DATOS (API PDO)
$conexionBD = null;
	
// 2. CONFIGURACIÓN DEL PROCESADOR: Vuelca el búfer de salida HTTP hacia el cliente
// Envía definitivamente todo el HTML acumulado y deshabilita el almacenamiento en búfer.
ob_end_flush();
?>