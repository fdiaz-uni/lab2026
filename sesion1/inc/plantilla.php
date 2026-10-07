<?php 
/**
 * inc/plantilla.php
 * Definición de las funciones de generación de la "estructura base" de las páginas.
 */
function cabeceraPlantilla() { ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8"> 
	<!-- declaración codificación multilingüe. Atención! debe guardarse el archivo con la codificación declarada -->
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- proporciona información al navegador de cómo controlar las dimensiones de la página (ancho) y escalado -->
	
	<title><?php print constant('TITULO_PAGINA');?></title>
	<link rel="stylesheet" href="css/fichero-css-externo.css"> 
</head>
<body>
	
	<div class="encabezado"> <!-- Con HTML5 utilizar etiqueta HEADER -->
		<h1>Contenido encabezado pagina [<?php print constant('TITULO_PAGINA');?>]</h1> 
	</div>

	<div class="topnav"> <!-- Con HTML5 utilizar etiqueta NAV -->
		<ul>
            <li><a href="acerca_de.php">Quiénes somos</a></li>
            <li><a href="contacta.php">Contacto</a></li>
		</ul>
	</div>
<?php } ?>

<?php function piePlantilla() { ?>
 
	<div class="pie"> <!-- Con HTML5 utilizar etiqueta FOOTER -->
		Informaicón copyright
	</div>
	<script src="js/fichero-javascript-externo.js"></script> 
</body>
</html>
<?php } ?>


