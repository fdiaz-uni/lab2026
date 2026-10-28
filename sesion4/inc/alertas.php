<?php
/**
 * inc/alertas.php
 * Componente visual para la gestión estructurada y escalable de notificaciones.
 */

// 1. Se verifica si existe un resultado en la query string (URL) antes de actuar
if (isset($_GET['resultado']) && !empty($_GET['resultado'])) {
    
    // Se sanea la entrada por seguridad ante posibles manipulaciones de la URL (prevención inyección XSS)
    $claveAlerta = htmlspecialchars($_GET['resultado'], ENT_QUOTES, 'UTF-8');

    // 2. MAPA DE CONFIGURACIÓN DE ALERTAS (Se puede ir extendiendo...)
    // Cada clave asocia un mensaje y una clase contextual de Bootstrap de forma clara.
    $catalogoAlertas = [
        // Estados de Alta (Creación)
        "persona-added" => [
            "mensaje" => "<strong>¡Éxito!</strong> Persona registrada correctamente.",
            "clase"   => "alert-success"
        ],
        "persona-ko" => [
            "mensaje" => "<strong>¡Error!</strong> Hubo un fallo al registrar a la persona en el sistema.",
            "clase"   => "alert-danger"
        ],

        // Estados de Modificación (Actualización)
        "persona-updated" => [
            "mensaje" => "<strong>¡Éxito!</strong> Persona modificada correctamente.",
            "clase"   => "alert-success"
        ],
        "persona-update-ko" => [
            "mensaje" => "<strong>¡Error!</strong> Hubo un fallo al modificar los datos de la persona.",
            "clase"   => "alert-danger"
        ],
        
        // Estados de Borrado (Eliminación)
        "persona-deleted" => [
            "mensaje" => "<strong>¡Éxito!</strong> Persona borrada correctamente.",
            "clase"   => "alert-success"
        ],
        "persona-delete-ko" => [
            "mensaje" => "<strong>¡Error!</strong> Hubo un fallo al eliminar a la persona del sistema.",
            "clase"   => "alert-danger"
        ]
    ];

    // 3. PROCESAMIENTO Y RENDERIZADO DE LA ALERTA ESPECÍFICA, SEGÚN EL CASO
    // Se comprueba si la clave de la URL existe en nuestro catálogo indexado
    if (array_key_exists($claveAlerta, $catalogoAlertas)) {
        
        // Se extraen los atributos dinámicos desde el catálogo de alertas, utilizando la $claveAlerta recibida
        $mensajeHTML    = $catalogoAlertas[$claveAlerta]["mensaje"];
        $claseBootstrap = $catalogoAlertas[$claveAlerta]["clase"];
        ?>
        
        <!-- Bloque HTML universal,	 reutilizado para cualquier notificación -->
        <div class="container mt-3">
            <div class="alert <?= $claseBootstrap ?> alert-dismissible" role="alert">
                <?= $mensajeHTML ?>
            </div>
        </div>

        <?php 
    }
}
?>
