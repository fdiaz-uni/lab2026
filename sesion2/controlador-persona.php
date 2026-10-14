<?php 
//código inicializacion, siempre necesario!!!
require_once "inc/codigo_inicializacion.php";

//funciones de datos (modelo) y saneamiento (si existen)
require_once "inc/codigo_funciones.php";
require_once "inc/modelo-persona.php";

/*
define("TITULO_PAGINA", "Controlador personas");
require_once "inc/plantilla.php"; 
cabeceraPlantilla();
// Nota!!!: Este archivo procesa lógica y redirige. 
// No debe pintar contenido en pantalla ni contener HTML visible (por eso no incluye inc/plantilla.php, 
// ni se invoca a cabeceraPlantilla(), ni a piePlantilla().
*/

if (array_key_exists('nuevaPersona', $_POST)) { 
//print_r($_POST);
	// Se capturan las entradas eliminando solo espacios en blanco sobrantes (sin mutar los caracteres)
    $nombre     = trim($_POST["fNombre"]);
    $email      = trim($_POST["fEmail"]);
    $website    = trim($_POST["fWebsite"]);
    $comentario = trim($_POST["fComentario"]);
    $sexo       = trim($_POST["fSexo"]);
	$resultado= nuevaPersona($conexionBD, $nombre, $email, $website, $comentario, $sexo);
	//$resultado=ERROR_ALTA_PERSONA;
	if ($resultado==OK)
		header ("Location: indice-personas.php?resultado=persona-added", true, 303);
	else 
		header ("Location: indice-personas.php?resultado=persona-ko", true, 303);
	exit();
}

/*
	piePlantilla();
*/

//código finalización, siempre necesario para hacer limpieza
require_once "inc/codigo_finalizacion.php";
?>
