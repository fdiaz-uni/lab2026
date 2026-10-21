<?php 
// 1. Inicialización del sistema y carga de módulos requeridos
include_once "inc/codigo_inicializacion.php"; 

require_once "inc/plantilla.php";
require_once "inc/modelo-persona.php"; 

define("TITULO_PAGINA", "Personas");
cabeceraPlantilla('personas'); 
?>

<div class="container">
    <h1>Personas</h1>

    <!-- i) Inclusión reutilizable del componente (Como no hay "id" en la URL, actuará como formulario de Alta) -->
    <?php include_once "inc/componente-persona.php"; ?>

    <!-- Bloque listado principal de registros -->
    <div class="well">
        <h3>Personas registradas</h3>
        
        <?php
        $sentenciaSQL = "SELECT cId, cNombre, cEmail FROM Persona";
        $resultado = getPersonas($conexionBD, $sentenciaSQL);

        if (count($resultado) > 0): 
        ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th style="width: 250px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultado as $fila): ?>
                        <?php 
                        // SANEAMIENTO CONTRA ATAQUES XSS EN LA SALIDA
                        $nombreSaneado = htmlspecialchars($fila["cNombre"], ENT_QUOTES, 'UTF-8');
                        $emailSaneado  = htmlspecialchars($fila["cEmail"], ENT_QUOTES, 'UTF-8');
                        $idSeguro      = urlencode($fila["cId"]); 
                        ?>
                        <tr>
                            <td><?= $nombreSaneado ?></td>
                            <td><?= $emailSaneado ?></td>
                            <td>
                                <!-- Se transforman las listas en elementos alineados en bloque/línea -->
                                <div class="btn-group" role="group">
                                    <!-- Enlace seguro para modificar vía GET -->
                                    <a href="modificar-persona.php?id=<?= $idSeguro ?>" class="btn btn-sm btn-warning" style="margin-right: 5px;">
                                        modificar
                                    </a>
                                    
                                    <!-- Formulario seguro en línea para el Borrado mediante POST -->
                                    <form action="controlador-persona.php" method="post" style="display: inline-block;" onsubmit="return confirm('¿Está seguro de que desea eliminar a esta persona?');">
                                        <input type="hidden" name="fId" value="<?= $fila["cId"] ?>" />
                                        <input type="hidden" name="borrarPersona" value="1" />
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            borrar
                                        </button>
                                    </form>
                                </div>
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
piePlantilla();
include_once "inc/codigo_finalizacion.php"; 
?>
