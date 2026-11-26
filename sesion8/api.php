<?php
//ini_set('display_errors', 1); error_reporting(E_ALL);
/**
 * api.php
 * Controlador Frontal REST (Único punto de entrada para datos JSON)
 */

// 1. CARGAR EL ENTORNO (sesiones, constantes, conexión MySQLi POO)
require_once "inc/codigo_inicializacion.php";

// 2. CONFIGURAR LA RESPUESTA GLOBAL EN FORMATO JSON y UTF-8 (mediante establecimiento cabecera-http "Content-Type")
header("Content-Type: application/json; charset=UTF-8");

// 3. CAPTURAR EL VERBO HTTP (DE LA PETICIÓN), EL RECURSO SOLICITADO Y EL ID OPCIONAL
$metodo  = $_SERVER['REQUEST_METHOD']; // GET, POST, PUT, DELETE
$recurso = $_GET['recurso'] ?? '';
$accion  = $_GET['accion'] ?? '';     // para sub-acciones como login, logout, miperfil
$id      = isset($_GET['id']) ? intval($_GET['id']) : null;

// 4. Función auxiliar para enviar respuestas JSON estandarizadas y cortar ejecución
function responderJSON($codigoHTTP, $datos) {
    http_response_code($codigoHTTP);
    echo json_encode($datos, JSON_UNESCAPED_UNICODE);
    exit();
}

// 5. Enrutamiento por recurso
switch ($recurso) {
    case 'auth':
        require_once RAIZ_FISICA . "src/controladores/usuario_rest.php";
        break;

    case 'personas':
        // Comnprobación sesión usuario acción privada
        if (!isset($_SESSION['usuario'])) {
			// 401 Unauthorized
            responderJSON(401, [
                "estado"  => "error",
                "mensaje" => "No autorizado. Debes iniciar sesión para acceder a este recurso."
            ]);
        }
        require_once RAIZ_FISICA . "src/controladores/persona_rest.php";
        break;

    default: 
		//404 Not found
        responderJSON(404, [
            "estado"  => "error",
            "mensaje" => "Recurso no encontrado."
        ]);
        break;
}

?>
