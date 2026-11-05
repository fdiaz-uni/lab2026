<?php function cabeceraPlantilla($pagina_actual = '') { //ATENCIÓN: Declarado parámetro en cabeceraPlantilla()!!!?> 
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8"> 
	<!-- declaración codificación multilingüe. Atención! debe guardarse el archivo con la codificación declarada -->
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- proporciona información al navegador de cómo controlar las dimensiones de la página (ancho) y escalado -->
	
	<title><?= TITULO_PAGINA ?></title>
	<!--link rel="stylesheet" href="<--?= RAIZ_URL ?>recursos/css/fichero-css-externo.css"--> 
	<!--Se sustituye una hoja de estilo externo por la carga de Bootstrap-->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

</head>
<body>
    
    <!-- Encabezado Semántico HTML5 -->
    <header class="container encabezado">
        <div class="page-header">
            <h1>Contenido encabezado página <small>[<?= TITULO_PAGINA ?>]</small></h1>
        </div>
    </header>

    <!-- Barra de Navegación Semántica HTML5 -->
    <nav class="container">
        <ul class="nav nav-pills">
            <!-- Se evalua dinámicamente si esta pestaña debe estar activa -->
            <li class="<?= $pagina_actual === 'personas' ? 'active' : '' ?>">
                <a href="<?= RAIZ_URL ?>index.php?seccion=personas">Personas</a>
            </li>
            <li class="<?= $pagina_actual === 'otra' ? 'active' : '' ?>">
                <a href="<?= RAIZ_URL ?>index.php?seccion=otra">Otra Sección</a>
            </li>
        </ul>
    </nav>

    <!-- Inclusión automática de las alertas del sistema justo debajo de la navegación -->
    <?php include_once RAIZ_FISICA . "inc/alertas.php"; ?>

    <!-- Apertura del contenedor principal que envolverá el contenido de las vistas -->
    <main>
<?php } ?>

<?php function piePlantilla() { ?>
    </main> <!-- Cierre de main -->
 
    <!-- Pie de Página Semántico HTML5 -->
    <footer class="container pie text-muted">
        <hr>
        <p>Información copyright &copy; <?= date('Y') ?></p>
    </footer>

    <!-- Scripts de comportamiento adicionales de la aplicación -->
    <script src="<?= RAIZ_URL ?>recursos/js/fichero-javascript-externo.js"></script> 
</body>
</html>
<?php } ?>




