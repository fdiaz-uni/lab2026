<?php 
/**
 * inc/componente-persona.php
 * Componente de formulario dinámico y reutilizable para Alta y Modificación de la entidad Persona.
 */

// 1. Captura segura del ID desde la URL (si no existe o es inválido, equivale a 0)
$id = intval($_GET["id"] ?? 0);
$etiqueta = ($id === 0) ? "Nueva persona" : "Modificar persona";

// 1. RECUPERACIÓN DEL CONTEXTO DE ERRORES DE SESIÓN
$erroresContexto = $_SESSION['errores_persona'] ?? [];
$datosContexto   = $_SESSION['datos_persona'] ?? [];

// Limpiamos los buffers de error de la sesión inmediatamente (Patrón Flash Data)
unset($_SESSION['errores_persona']);
unset($_SESSION['datos_persona']);

// 2. REGLA DE CARGA DE DATOS (Prioridad: 1º Errores de Sesión, 2º Base de Datos, 3º Vacío)
$fila = [
    "cNombre" => "", "cEmail" => "", "cWebsite" => "", "cComentario" => "", "cSexo" => "M"
];

if (!empty($datosContexto)) {
    // Si venimos de un fallo de validación, recuperamos lo que escribió el usuario
    $fila = $datosContexto;
} elseif ($id !== 0) {
    // Si es una modificación normal, cargamos desde la base de datos
	// Ojo!!! recuperando ahora el contacto/persona siempre que sea del usuario logueado, que se consigue de la SESIÓN de usuario
	//$personaRegistrada = getPersona($conexionBD, $id);
	$personaRegistrada = getPersona($conexionBD, $id, $_SESSION['usuario']['cId']);

    // Si la consulta fue exitosa y devolvió datos, sobrescribimos los valores por defecto
    if (is_array($personaRegistrada) && !empty($personaRegistrada)) {
        $fila = $personaRegistrada;
    } else {
		// Si devuelve un código de error (no es un array), cortamos la carga y redirigimos con alerta
		header("Location: " . RAIZ_URL . "index.php?seccion=personas&resultado=persona-no-existe", true, 303);
		exit();

	}
}

?>
        

<div class="well">
    <h3><?= $etiqueta ?></h3>

    <!-- SE AÑADE ID="formPersona" AL ELEMENTO FORM -->
	<form action="<?= RAIZ_URL ?>src/controladores/persona.php" method="post" id="formPersona" class="form">
        
        <!-- Campo Nombre -->
        <div class="form-group <?= isset($erroresContexto['nombre']) ? 'has-error' : '' ?>">
            <div><label for="fNombre" class="control-label">Nombre: </label></div>
			<!-- Atributos requeridos por jQuery Validation: required y minlength -->
            <input type="text" name="fNombre" value="<?= htmlspecialchars($fila["cNombre"] ?? "", ENT_QUOTES, 'UTF-8') ?>" id="fNombre" class="form-control" required minlength="3">
            <?php if (isset($erroresContexto['nombre'])): ?>
                <span class="help-block"><span class="glyphicon glyphicon-exclamation-sign"></span> <?= $erroresContexto['nombre'] ?></span>
            <?php endif; ?>
        </div>

        <!-- Campo Email -->
        <div class="form-group <?= isset($erroresContexto['email']) ? 'has-error' : '' ?>">
            <div><label for="fEmail" class="control-label">E-mail: </label></div>
            <!-- Atributos requeridos por jQuery Validation: required y email -->
			<input type="text" name="fEmail" value="<?= htmlspecialchars($fila["cEmail"] ?? "", ENT_QUOTES, 'UTF-8') ?>" id="fEmail" class="form-control" required email="true">
            <?php if (isset($erroresContexto['email'])): ?>
                <span class="help-block"><span class="glyphicon glyphicon-exclamation-sign"></span> <?= $erroresContexto['email'] ?></span>
            <?php endif; ?>
        </div>
			
        <!-- Campo Website -->
        <div class="form-group <?= isset($erroresContexto['website']) ? 'has-error' : '' ?>">
            <div><label for="fWebsite" class="control-label">Website: </label></div>
            <!-- Atributo requerido por jQuery Validation: url -->
			<input type="text" name="fWebsite" value="<?= htmlspecialchars($fila["cWebsite"] ?? "", ENT_QUOTES, 'UTF-8') ?>" id="fWebsite" class="form-control" url="true">
            <?php if (isset($erroresContexto['website'])): ?>
                <span class="help-block"><span class="glyphicon glyphicon-exclamation-sign"></span> <?= $erroresContexto['website'] ?></span>
            <?php endif; ?>
        </div>
				
        <!-- Campo Comentario -->
        <div class="form-group">
            <div><label for="fComentario">Comentario: </label></div>
            <textarea name="fComentario" id="fComentario" rows="4" class="form-control"><?= htmlspecialchars($fila["cComentario"] ?? "", ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>
				
        <!-- Campo Sexo -->
        <div class="form-group <?= isset($erroresContexto['sexo']) ? 'has-error' : '' ?>">
            <div><label class="control-label">Sexo: </label></div>
            <div class="radio-inline">
                <input type="radio" name="fSexo" id="fSexoM" value="M" <?= (($fila["cSexo"] ?? "M") === "M") ? "checked" : "" ?>> Mujer
            </div>
            <div class="radio-inline">
                <input type="radio" name="fSexo" id="fSexoH" value="H" <?= (($fila["cSexo"] ?? "") === "H") ? "checked" : "" ?>> Hombre
            </div>
            <?php if (isset($erroresContexto['sexo'])): ?>
                <span class="help-block" style="display:block;"><span class="glyphicon glyphicon-exclamation-sign"></span> <?= $erroresContexto['sexo'] ?></span>
            <?php endif; ?>
        </div>
			
        <div>
            <!-- Campos ocultos compatibles con controlador-persona.php -->
            <input type="hidden" name="nuevaModificarPersona" value="1" />
            <input type="hidden" name="fId" value="<?= $id ?>" id="fId" />
            <input type="submit" name="btnModificarPersona" value="<?= $etiqueta ?>" class="btn btn-primary"/>
			<?php if (!empty($erroresContexto)): ?>
                <span class="text-danger" style="margin-left:15px;"><strong>Por favor, corrige los errores del formulario.</strong></span>
            <?php endif; ?>
        </div>
    </form>
</div>