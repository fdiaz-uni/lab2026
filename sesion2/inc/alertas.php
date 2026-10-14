<?php
/**
 * inc/alertas.php
 * Componente visual para la gestión y renderizado de notificaciones del sistema.
 */

// 1. Se verifica si existe un resultado en la query string (URL) antes de actuar
if (isset($_GET['resultado'])) {
    
    // Se sanea la entrada por seguridad ante posibles manipulaciones de la URL (prevención inyección XSS)
    $resultado = htmlspecialchars($_GET['resultado'], ENT_QUOTES, 'UTF-8');
    
    // 2. Se discrimina el tipo de mensaje a mostrar mediante una estructura limpia
    if ($resultado === "persona-added") { ?>
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible" role="alert">
                <strong>¡Éxito!</strong> Persona registrada correctamente.
            </div>
        </div>
    <?php } 
    elseif ($resultado === "persona-ko") { ?>
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible" role="alert">
                <strong>¡Error!</strong> Hubo un fallo al registrar a la persona en el sistema.
            </div>
        </div>
    <?php }
}
?>