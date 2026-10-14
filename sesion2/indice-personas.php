<?php 
// código inicialización, siempre necesario
include_once "inc/codigo_inicializacion.php"; 

// código plantilla y funciones del modelo de datos
require_once "inc/plantilla.php";
require_once "inc/modelo-persona.php"; // <--- Ahora se incluye aquí

define("TITULO_PAGINA", "Personas");
cabeceraPlantilla('personas'); //ATENCIÓN!!!, añadido parámetro a la función cabeceraPlantilla($pagina_actual = '');
?>

<div class="container">
    <h1>Personas</h1>

    <!-- Bloque de inserción nueva persona -->
    <div class="well">
        <h3>Añadir persona</h3>
 		<form action="controlador-persona.php" method="post" class="form-inline">
			<div><label for="fNombre">Nombre: </label></div>
			<div><input type="text" name="fNombre" id="fNombre" class="form-control"></div>

			<div><label for="fEmail">E-mail: </label></div>
			<div><input type="text" name="fEmail" id="fEmail" class="form-control"></div>
			
			<div><label for="fWebsite">Website: </label></div>
			<div><input type="text" name="fWebsite" id="fWebsite" class="form-control"></div>
				
			<div><label for="fComentario">Comentario: </label></div>
			<div><textarea name="fComentario" id="fComentario" rows="5" cols="40"></textarea></div>
				
			<div><label for="fSexo">Sexo: </label></div>
			<div>
				<input type="radio" name="fSexo" id="fSexo" value="M" class="form-control" checked> Mujer 
				<input type="radio" name="fSexo" id="fSexo" value="H" class="form-control"> Hombre 
			</div>
				
			<div>
				<input type="hidden" name="nuevaPersona" value="1" />
				<input type="submit" name="btnNuevaPersona" value="Nueva Persona" class="btn btn-primary"/>
			</div>
		</form>
	</div> <!-- /.well -->



    <!-- Bloque listado principal de registros -->
    <div class="well">
        <h3>Personas registradas</h3>
        
        <?php
        // Consultamos los datos en bruto al modelo a través de nuestra abstracción
        $sentenciaSQL = "SELECT cId, cNombre, cEmail FROM Persona";
        $resultado = getPersonas($conexionBD, $sentenciaSQL);

        if (count($resultado) > 0): 
        ?>
            <!-- Estructura de presentación HTML pura -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultado as $fila): ?>
                        <?php 
                        // SANEAMIENTO DE SALIDA (Mitigación estricta de vulnerabilidades XSS)
                        $nombreSaneado = htmlspecialchars($fila["cNombre"], ENT_QUOTES, 'UTF-8');
                        $emailSaneado  = htmlspecialchars($fila["cEmail"], ENT_QUOTES, 'UTF-8');
                        
                        // Saneamiento de parámetros para cadenas de consulta URL de forma segura
                        $idSeguro = urlencode($fila["cId"]); 
                        ?>
                        <tr>
                            <td><?= $nombreSaneado ?></td>
                            <td><?= $emailSaneado ?></td>
                            <td>
                                <ul class="nav nav-pills">
                                    <li>
                                        <a href="modificar-persona.php?id=<?= $idSeguro ?>">modificar</a>
                                    </li>
                                    <li>
                                        <a href="indice-personas.php">otraAcción sobre persona</a>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
        <?php else: ?>
            <p class="text-muted">No hay personas registradas en el sistema.</p>
        <?php endif; ?>
        
    </div> <!-- /.well -->
</div> <!-- /.container -->

<?php 
// con esta invocación se genera el HTML que aparece al FINAL de todas las páginas (plantilla)
piePlantilla();
// código finalización, limpieza final
include_once "inc/codigo_finalizacion.php"; 
?>
