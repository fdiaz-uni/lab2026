<?php
/**
 * src/controladores/usuario_rest.php
 * Controlador RESTful para la gestión de sesión de usuario.
 * Endpoints:
 *  - POST /api.php?recurso=auth&accion=login
 *  - POST /api.php?recurso=auth&accion=logout
 *  - GET  /api.php?recurso=auth&accion=miperfil
 */

require_once RAIZ_FISICA . "src/modelos/usuario.php";

switch ($accion) {

    // ========================================================================
    // 1. INICIO DE SESIÓN (POST /api.php?recurso=auth&accion=login)
    // ========================================================================
    case 'login':
        if ($metodo !== 'POST') {
			// 405 Method Not Allowed
            responderJSON(405, ["estado" => "error", "mensaje" => "Método no permitido. Use POST."]);
        }

        // Guarda de estado: Si ya está logueado, devolvemos sus datos actuales
        if (isset($_SESSION['usuario'])) {
			//200 OK
            responderJSON(200, [
                "estado"  => "ok",
                "mensaje" => "Ya existe una sesión activa.",
                "usuario" => $_SESSION['usuario']
            ]);
        }

        $entradaJSON = json_decode(file_get_contents("php://input"), true);
        if (!is_array($entradaJSON)) {
			// 400 Bad Request
            responderJSON(400, ["estado" => "error", "mensaje" => "Cuerpo de petición JSON inválido."]);
        }

        $email    = trim($entradaJSON['email'] ?? '');
        $password = $entradaJSON['password'] ?? '';

        // Validación en servidor
        $errores = [];
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errores['email'] = "Introduce un correo electrónico válido.";
        }
        if (empty($password)) {
            $errores['password'] = "La contraseña no puede estar vacía.";
        }

        if (!empty($errores)) {
			// 400 Bad Request
            responderJSON(400, [
                "estado"  => "error",
                "mensaje" => "Errores de validación en los datos enviados.",
                "errores" => $errores
            ]);
        }

        // Autenticación contra el modelo
        $usuarioAutenticado = autenticarUsuario($conexionBD, $email, $password);

        if ($usuarioAutenticado) {
            session_regenerate_id(true); // Prevención Session Fixation mediante la regeneración id de sesión
            $_SESSION['usuario'] = $usuarioAutenticado; // Guardar la sesión de usuario (en $_SESSION)

			// 200 OK
            responderJSON(200, [
                "estado"  => "ok",
                "mensaje" => "Sesión iniciada correctamente.",
                "usuario" => $usuarioAutenticado
            ]);
        } else {
			// 401 Unauthorized
            responderJSON(401, [
                "estado"  => "error",
                "mensaje" => "El correo electrónico o la contraseña son incorrectos."
            ]);
        }
        break;

    // ========================================================================
    // 2. CIERRE DE SESIÓN (POST /api.php?recurso=auth&accion=logout)
    // ========================================================================
    case 'logout':
        if ($metodo !== 'POST') {
			// 405 Method Not Allowed
            responderJSON(405, ["estado" => "error", "mensaje" => "Método no permitido. Use POST."]);
        }

        // Guarda de estado: Solo permitimos logout si hay sesión activa
        if (!isset($_SESSION['usuario'])) {
			// 401 Unauthorized
            responderJSON(401, ["estado" => "error", "mensaje" => "No hay ninguna sesión activa que cerrar."]);
        }

        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        }
        session_destroy();

		// 200 OK
        responderJSON(200, [
            "estado"  => "ok",
            "mensaje" => "Sesión cerrada correctamente."
        ]);
        break;

    // ========================================================================
    // 3. CONSULTAR ESTADO DE SESIÓN (GET /api.php?recurso=auth&accion=miperfil)
    // ========================================================================
    case 'miperfil':
        if ($metodo !== 'GET') {
			// 405 Method Not Allowed
            responderJSON(405, ["estado" => "error", "mensaje" => "Método no permitido. Use GET."]);
        }

        if (isset($_SESSION['usuario'])) {
			// 200 OK
            responderJSON(200, [
                "estado"  => "ok",
                "usuario" => $_SESSION['usuario']
            ]);
        } else {
			// 401 Unauthorized
            responderJSON(401, [
                "estado"  => "error",
                "mensaje" => "Usuario no autenticado."
            ]);
        }
        break;

    default:
		// 404 Not Found
        responderJSON(404, ["estado" => "error", "mensaje" => "Acción de autenticación no encontrada."]);
        break;
}
?>