<?php 
/**
 * inc/componente-persona.php
 * Componente de formulario dinámico y reutilizable para Alta y Modificación de la entidad Persona.
 */

// 1. Captura segura del ID desde la URL (si no existe o es inválido, equivale a 0)
$id = intval($_GET["id"] ?? 0);

// 2. Se define una estructura inicial limpia por defecto (Útil para el caso de Añadir)
$fila = [
    "cNombre"     => "",
    "cEmail"      => "",
    "cWebsite"    => "",
    "cComentario" => "",
    "cSexo"       => "M" // Marcamos Mujer por defecto para evitar que este campo se envíe vacío en el $_POST
];

if ($id === 0) {
    $etiqueta = "Nueva persona";
} else {
    $etiqueta = "Modificar persona";
    // Recuperamos los datos reales desde el modelo
    $personaRegistrada = getPersona($conexionBD, $id);
    
    // Si la consulta fue exitosa y devolvió datos, sobrescribimos los valores por defecto
    if (is_array($personaRegistrada) && !empty($personaRegistrada)) {
        $fila = $personaRegistrada;
    }
}
?>

<div class="well">
    <h3><?= $etiqueta ?></h3>

    <form action="controlador-persona.php" method="post" class="form-inline">
        
        <div><label for="fNombre">Nombre: </label></div>
		<div><input type="text" name="fNombre" value="<?= htmlspecialchars($fila["cNombre"], ENT_QUOTES, 'UTF-8') ?>" id="fNombre" class="form-control"></div>

        <div><label for="fEmail">E-mail: </label></div>
        <div><input type="text" name="fEmail" value="<?= htmlspecialchars($fila["cEmail"], ENT_QUOTES, 'UTF-8') ?>" id="fEmail" class="form-control"></div>
			
        <div><label for="fWebsite">Website: </label></div>
        <div><input type="text" name="fWebsite" value="<?= htmlspecialchars($fila["cWebsite"], ENT_QUOTES, 'UTF-8') ?>" id="fWebsite" class="form-control"></div>
				
        <div><label for="fComentario">Comentario: </label></div>
        <div><textarea name="fComentario" id="fComentario" rows="5" cols="40" class="form-control"><?= htmlspecialchars($fila["cComentario"], ENT_QUOTES, 'UTF-8') ?></textarea></div>
				
        <div><label>Sexo: </label</div>
        <div><div class="radio-inline">
                <input type="radio" name="fSexo" id="fSexoM" value="M" <?= ($fila["cSexo"] === "M") ? "checked" : "" ?>> Mujer
            </div>
            <div class="radio-inline">
                <input type="radio" name="fSexo" id="fSexoH" value="H" <?= ($fila["cSexo"] === "H") ? "checked" : "" ?>> Hombre
            </div>
        </div>
			
        <div>
            <!-- Campos ocultos compatibles con controlador-persona.php -->
            <input type="hidden" name="nuevaModificarPersona" value="1" />
            <input type="hidden" name="fId" value="<?= $id ?>" id="fId" />
            <input type="submit" name="btnModificarPersona" value="<?= $etiqueta ?>" class="btn btn-primary"/>
        </div>
    </form>
</div>