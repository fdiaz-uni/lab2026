<?php
	function comprobar_entrada($dato) { /* Función para prevenir inyección código JS */
		$dato = trim($dato);
		$dato = stripslashes($dato);
		//$dato = htmlspecialchars($dato); // sustituye " < > & por sus entidades html con nombre
		$dato = htmlspecialchars($dato, ENT_QUOTES, 'UTF-8'); //filtro aún más robusto, sustituye también comillas simples
		return $dato;
	}
	
	function comprobar_entrada2($conexion, $dato) { /* Función para prevenir inyección código JS y SQL */
		$dato = mysqli_real_escape_string($conexion, $dato);
		$dato = trim($dato);
		$dato = stripslashes($dato);
		$dato = htmlspecialchars($dato);
		return $dato;
	}
?>