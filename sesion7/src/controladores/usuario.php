<?php
/**
 * controlador-usuario.php
 * Controlador único para la gestión de acciones sobre la entidad Usuario (Patrón MVC + PRG).
 * Este archivo procesa lógica de negocio en segundo plano y redirige; no genera HTML.
 */
// 1. Inicialización básica del script: conexión BD, constantes y buffers HTTP
require_once "../../inc/codigo_inicializacion.php";

// 2. Inclusión explícita de la lógica de datos (Modelo)
require_once RAIZ_FISICA . "src/modelos/usuario.php";


/* ============================================================================
   ACCIÓN 1: INICIO DE SESIÓN (POST)
   ============================================================================ */
if (array_key_exists('login', $_POST)) {
	// COMPROBACIÓN DEL ESTADO DE LA SESIÓN DE USUARIO: Si ya hay una sesión (de usuario) activa, se evita un segundo login
    if (isset($_SESSION['usuario'])) {
        header("Location: " . RAIZ_URL . "index.php?seccion=personas", true, 303);
        exit();
    }
	
    $email    = trim($_POST['fEmail'] ?? "");
    $password = $_POST['fPassword'] ?? ""; // No se aplica trim a contraseñas por si contienen espacios intencionales

    $errores = [];
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores['email'] = "Introduce un correo electrónico válido.";
    }
    if (empty($password)) {
        $errores['password'] = "La contraseña no puede estar vacía.";
    }

    if (!empty($errores)) {
        $_SESSION['errores_login'] = $errores;
        $_SESSION['datos_login']   = ['email' => $email];
        header("Location: " . RAIZ_URL . "index.php?seccion=login", true, 303);
        exit();
    }

    $usuarioAutenticado = autenticarUsuario($conexionBD, $email, $password);

    if ($usuarioAutenticado) {
        // Se regenera ID de sesión tras el login con por seguridad 
        session_regenerate_id(true);
        $_SESSION['usuario'] = $usuarioAutenticado;
        header("Location: " . RAIZ_URL . "index.php?seccion=personas&resultado=login-ok", true, 303);
    } else {
        $_SESSION['datos_login'] = ['email' => $email];
        header("Location: " . RAIZ_URL . "index.php?seccion=login&resultado=login-ko", true, 303);
    }
    exit();
}


/* ============================================================================
   ACCIÓN 2: CIERRE DE SESIÓN (POST)
   ============================================================================ */
   if (array_key_exists('logout', $_POST)) {
	   
	// COMPROBACIÓN DEL ESTADO DE LA SESIÓN DE USUARIO:: Solo se permite el logout, si realmente existe una sesión (de usuario) activa
    if (!isset($_SESSION['usuario'])) {
        header("Location: " . RAIZ_URL . "index.php?seccion=login&resultado=auth-required", true, 303);
        exit();
    }
    // Se limpia el array de sesión y se destryte la cookie/sesión en el servidor
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
    }
    session_destroy();

    header("Location: " . RAIZ_URL . "index.php?seccion=login&resultado=logout-ok", true, 303);
    exit();
}

// 3. Cierre limpio de la conexión a la base de datos y liberación de memoria
require_once RAIZ_FISICA . "inc/codigo_finalizacion.php";
?>
