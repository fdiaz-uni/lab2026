<?php
/**
 * src/controladores/persona_rest.php
 * Controlador RESTful para la entidad Persona.
 * Variables habilitadas por el controlador frontal "api.php": $conexionBD, $metodo, $id, responderJSON()
 */

require_once RAIZ_FISICA . "src/modelos/persona.php";

// Extraer el ID del usuario autenticado a partir de la sesión, con el fin de prevenir referencias IDOR
$idUsuario = intval($_SESSION['usuario']['cId']);

// Función interna de validación reutilizable para POST y PUT
function validarDatosPersona($datos) {
    $errores = [];
    $nombre  = trim($datos['nombre'] ?? '');
    $email   = trim($datos['email'] ?? '');
    $website = trim($datos['website'] ?? '');
    $sexo    = trim($datos['sexo'] ?? '');

    if (empty($nombre) || strlen($nombre) < 3) {
        $errores['nombre'] = "El nombre es obligatorio y debe tener al menos 3 caracteres.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores['email'] = "El formato del correo electrónico no es válido.";
    }
    if (!empty($website) && !filter_var($website, FILTER_VALIDATE_URL)) {
        $errores['website'] = "La dirección web no tiene un formato URL correcto.";
    }
    if (!in_array($sexo, ['H', 'M'])) {
        $errores['sexo'] = "Debe seleccionar un género válido ('H' o 'M').";
    }
    return $errores;
}

// ============================================================================
// ENRUTAMIENTO POR VERBO HTTP
// ============================================================================
switch ($metodo) {

    // 1. CONSULTA (GET /api.php?recurso=personas  o  GET /api.php?recurso=personas&id={ID})
    case 'GET':
        if ($id === null) {
            // Listar todos los contactos del usuario
            $lista = getPersonas($conexionBD, $idUsuario);
			// 200 OK
            responderJSON(200, ["estado" => "ok", "mensaje" => "Listado recuperado con éxito.", "listado" => $lista]);
        } else {
            // Obtener un contacto concreto {ID}
            $persona = getPersona($conexionBD, $id, $idUsuario);
            if ($persona === NO_EXISTE_PERSONA) {
				// 404 Not Found
                responderJSON(404, ["estado" => "error", "mensaje" => "Contacto no encontrado o sin permisos."]);
            } elseif ($persona === ERROR_CONSULTA_PERSONA) {
				// 500 Internal Server Error
                responderJSON(500, ["estado" => "error", "mensaje" => "Error interno al consultar la base de datos."]);
            } else {
				// 200 OK
                responderJSON(200, ["estado" => "ok", "mensaje" => "Contacto recuperado con éxito.", "contacto" => $persona]);
            }
        }
        break;

    // 2. CREACIÓN (POST /api.php?recurso=personas)
    case 'POST':
        // Leer el cuerpo JSON enviado por el cliente con los datos de la persona a registrar
		// SE DEBE leer el flujo de entrada crudo del servidor "php://input" y decodificarlo con json_decode()
        $entradaJSON = json_decode(file_get_contents("php://input"), true);
        if (!is_array($entradaJSON)) {
			// 400 Bad Request
            responderJSON(400, ["estado" => "error", "mensaje" => "Cuerpo de petición JSON inválido."]);
        }

        // Validación en el lado servidor (mediante función validarDatosPersona())
        $errores = validarDatosPersona($entradaJSON);
        if (!empty($errores)) {
			// 400 Bad Request
            responderJSON(400, [
                "estado"  => "error",
                "mensaje" => "Errores de validación en los datos enviados.",
                "errores" => $errores
            ]);
        }

        // Persistencia en el modelo
        $resultado = nuevaPersona(
            $conexionBD,
            $idUsuario,
            trim($entradaJSON['nombre']),
            trim($entradaJSON['email']),
            trim($entradaJSON['website'] ?? ''),
            trim($entradaJSON['comentario'] ?? ''),
            trim($entradaJSON['sexo'])
        );

        if ($resultado === OK) {
			// 201 Created
            responderJSON(201, [
                "estado"  => "ok",
                "mensaje" => "Persona registrada correctamente.",
                "id"      => $conexionBD->insert_id // Se devuelve el nuevo ID generado
            ]);
        } else {
            // 409 Conflict => para indicar colisión con el índice UNIQUE (email duplicado)
            responderJSON(409, ["estado" => "error", "mensaje" => "No se pudo registrar (posible email duplicado)."]);
        }
        break;

    // 3. ACTUALIZACIÓN (PUT /api.php?recurso=personas&id={ID})
    case 'PUT':
        if ($id === null || $id <= 0) {
			// 400 Bad Request
            responderJSON(400, ["estado" => "error", "mensaje" => "Debe especificar un ID válido en la URL para modificar."]);
        }
		// Leer el cuerpo JSON enviado por el cliente con los datos a modificar de la persona
		// SE DEBE leer el flujo de entrada crudo del servidor "php://input" y decodificarlo con json_decode()
        $entradaJSON = json_decode(file_get_contents("php://input"), true);
        if (!is_array($entradaJSON)) {
			// 400 Bad Request
            responderJSON(400, ["estado" => "error", "mensaje" => "Cuerpo de petición JSON inválido."]);
        }

		// Validación en el lado servidor (mediante función validarDatosPersona())
        $errores = validarDatosPersona($entradaJSON);
        if (!empty($errores)) {
			// 400 Bad Request
            responderJSON(400, [
                "estado"  => "error",
                "mensaje" => "Errores de validación en los datos enviados.",
                "errores" => $errores
            ]);
        }

        // Persistencia en el modelo
		$resultado = modificarPersona(
            $conexionBD,
            $id,
            $idUsuario,
            trim($entradaJSON['nombre']),
            trim($entradaJSON['email']),
            trim($entradaJSON['website'] ?? ''),
            trim($entradaJSON['comentario'] ?? ''),
            trim($entradaJSON['sexo'])
        );

        if ($resultado === OK) {
			// 200 OK
            responderJSON(200, ["estado" => "ok", "mensaje" => "Persona modificada correctamente."]);
        } elseif ($resultado === NO_EXISTE_PERSONA) {
			// 404 Not Found
            responderJSON(404, ["estado" => "error", "mensaje" => "El contacto no existe o no tienes permisos para editarlo."]);
        } else {
			// 409 Conflict
            responderJSON(409, ["estado" => "error", "mensaje" => "Error al actualizar (posible email duplicado)."]);
        }
        break;

    // 4. ELIMINACIÓN (DELETE /api.php?recurso=personas&id={ID})
    case 'DELETE':
        if ($id === null || $id <= 0) {
			// 400 Bad Request
            responderJSON(400, ["estado" => "error", "mensaje" => "Debe especificar un ID válido en la URL para eliminar."]);
        }
		
		// Invocación al modelo
        $resultado = borrarPersona($conexionBD, $id, $idUsuario);

        if ($resultado === OK) {
			// 200 OK
            responderJSON(200, ["estado" => "ok", "mensaje" => "Persona eliminada correctamente."]);
        } elseif ($resultado === NO_EXISTE_PERSONA) {
			// 404 Not Found
            responderJSON(404, ["estado" => "error", "mensaje" => "El contacto no existe o no tienes permisos para borrarlo."]);
        } else {
			// 500 Internal Server Error
            responderJSON(500, ["estado" => "error", "mensaje" => "Error interno al eliminar el registro."]);
        }
        break;

    // 5. MÉTODO DE PETICIÓN HTTP NO SOPORTADO
    default:
		// 405 Method Not Allowed
        responderJSON(405, ["estado" => "error", "mensaje" => "Método HTTP no permitido para este recurso."]);
        break;
}
?>