<?php 
/**
 * controlador-persona.php
 * Controlador único para la gestión de acciones sobre la entidad Persona (Patrón MVC + PRG).
 * Este archivo procesa lógica de negocio en segundo plano y redirige; no genera HTML.
 */

// 1. Inicialización básica del script: conexión BD, constantes y buffers HTTP
require_once "../../inc/codigo_inicializacion.php";

// 2. Inclusión explícita de la lógica de datos (Modelo)
require_once RAIZ_FISICA . "src/modelos/persona.php";


/* ============================================================================
   ACCIÓN A: ALTA O MODIFICACIÓN DE REGISTROS (Formulario Unificado)
   ============================================================================ */
if (array_key_exists('nuevaModificarPersona', $_POST)) { 
    
    // Captura segura del identificador (forzado a entero)
    $id         = intval($_POST["fId"] ?? 0);
    
    // Captura limpia de entradas en bruto eliminando solo espacios en blanco sobrantes
    $nombre     = trim($_POST["fNombre"] ?? "");
    $email      = trim($_POST["fEmail"] ?? "");
    $website    = trim($_POST["fWebsite"] ?? "");
    $comentario = trim($_POST["fComentario"] ?? "");
    $sexo       = trim($_POST["fSexo"] ?? "");
    
    // CASO A.1: Si el ID es cero, se interpreta que se trata de una NUEVA PERSONA
    if ($id === 0) { 
        $resultado = nuevaPersona($conexionBD, $nombre, $email, $website, $comentario, $sexo);
        
        if ($resultado === OK) {
            $notificacion = "persona-added";
        } else {
            $notificacion = "persona-ko";
        }
    } 
    // CASO A.2: Si el ID contiene un valor numérico válido, se procede a MODIFICAR
    else {
        $resultado = modificarPersona($conexionBD, $id, $nombre, $email, $website, $comentario, $sexo);
        
        if ($resultado === OK) {
            $notificacion = "persona-updated";
        } else {
            $notificacion = "persona-update-ko"; // Código específico para error en edición
        }
    }
    
    // Aplicación estricta del patrón PRG tras procesar el formulario (por cualquier camino de ejecución, se redirige)
    header("Location: " . RAIZ_URL . "index.php?seccion=personas&resultado=$notificacion", true, 303);
    exit();
}


/* ============================================================================
   ACCIÓN B: ELIMINACIÓN DE REGISTROS
   ============================================================================ */
if (array_key_exists('borrarPersona', $_POST)) { 
    
    // Captura y aislamiento del ID que se desea eliminar
    $id = intval($_POST["fId"] ?? 0);
        
    $resultado = borrarPersona($conexionBD, $id);
    
    if ($resultado === OK) {
        $notificacion = "persona-deleted";
    } else {
        $notificacion = "persona-delete-ko"; // Código específico para error en borrado
    }
    
    // Redirección limpia al índice tras completar la eliminación
    header("Location: " . RAIZ_URL . "index.php?seccion=personas&resultado=$notificacion", true, 303);
    exit();
}


// 3. Cierre limpio de la conexión a la base de datos y liberación de memoria
require_once RAIZ_FISICA . "inc/codigo_finalizacion.php";
?>

