<?php
/**
 * inc/modelo-persona.php
 * Modelo de datos para la entidad Persona (API MySQLi Orientada a Objetos).
 */

/**
 * Función PHP que encapsula la inserción segura de una nueva persona.
 * Utiliza Consultas Preparadas (Prepared Statements) para blindar el sistema ante SQLi (inyección de código SQL).
 */
function nuevaPersona($conexionBD, $idUsuario, $nombre, $email, $website, $comentario, $sexo) {
    // 1. Preparar la sentencia directamente desde el objeto de conexión
	$stmt = $conexionBD->prepare('INSERT INTO Persona (cIdUsuario, cNombre, cEmail, cWebsite, cComentario, cSexo) VALUES (?, ?, ?, ?, ?, ?)');
    
    $okFlag = false;
    if ($stmt) {
        // 2. Vincular los parámetros por referencia (s = string)
        $stmt->bind_param("isssss", $idUsuario, $nombre, $email, $website, $comentario, $sexo);

        // 3. Ejecutar la consulta en el servidor
        try {
            $okFlag = $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            $okFlag = false; 
        }

        // 4. Cerrar y liberar la sentencia
        $stmt->close();
    }

    // Retorno de códigos de estado definidos en el inicializador
    if ($okFlag) {
        return OK;
    } else {
        return ERROR_ALTA_PERSONA;
    }
}


/**
 * CONTRAEJEMPLO DIDÁCTICO - LO QUE NO HAY QUE HACER!!!
 * Función que emula la inserción de una nueva persona de forma INSEGURA.
 * Muestra el peligro de concatenar variables directamente en la cadena SQL.
 *
 * function nuevaPersona_vInsegura($conexionBD, $nombre, $email, $website, $comentario, $sexo) {
 *     // ALERTA DE SEGURIDAD: Vulnerable a Inyección SQL (SQLi). 
 *     // Si un atacante escribe en el campo nombre: '; DROP TABLE Persona; --
 *     // Alterará malintencionadamente la estructura de la consulta final ejecutada.
 *     
 *     $sentenciaSQL = "INSERT INTO Persona (cNombre, cEmail, cWebsite, cComentario, cSexo) 
 *                      VALUES ('$nombre', '$email', '$website', '$comentario', '$sexo')";
 *     
 *     if ($conexionBD->query($sentenciaSQL)) {
 *         return OK;
 *     } else {
 *         return ERROR_ALTA_PERSONA;
 *     }
 * }
 */

/**
 * Función PHP que encapsula la consulta segura de una persona por su ID.
 */
function getPersona($conexionBD, $idPersona, $idUsuario) {
    // 1. Preparar la sentencia de selección
    $stmt = $conexionBD->prepare('SELECT cNombre, cEmail, cWebsite, cComentario, cSexo FROM Persona WHERE cId = ? AND cIdUsuario = ?');

    $fila = array();
    $okFlag = false;
    if ($stmt) {
        // 2. Vincular el parámetro del ID de la persona consultada y el ID del usuario que hace la consulta, el usuario logueado (i = entero)
        $stmt->bind_param("ii", $idPersona, $idUsuario);

        // 3. Ejecutar la consulta
        $okFlag = $stmt->execute();
        
        // 4. Obtener el buffer de resultados 
        $resultado = $stmt->get_result();
        
        // Si no hay filas, el ID no existe o pertenece a otro usuario
        if (!$resultado || $resultado->num_rows === 0) {
            $stmt->close();
            return NO_EXISTE_PERSONA;
        }
		$fila = $resultado->fetch_assoc();
        // 5. Cerrar la sentencia
        $stmt->close();
    }
	if ($okFlag) {
		return $fila;
	} else {
		return ERROR_CONSULTA_PERSONA;
    }
}

/**
 * Función PHP que encapsula la obtención de todo el listado de personas.
 */
function getPersonas($conexionBD, $idUsuario) {
    // Preparar consulta, ahora con el ID del usuario logueado
	$stmt = $conexionBD->prepare('SELECT cId, cNombre, cEmail FROM Persona WHERE cIdUsuario = ?');

    $array_filas = [];
    if ($stmt) {
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        while ($fila = $resultado->fetch_assoc()) {
            $array_filas[] = $fila;
        }
        $stmt->close();
    }
	return $array_filas;
}

/**
 * Función PHP que encapsula la actualización segura de una persona existente.
 */
function modificarPersona($conexionBD, $idPersona, $idUsuario, $nombre, $email, $website, $comentario, $sexo) {
    // 1. Preparar la sentencia directamente desde el objeto de conexión, ahora con el ID de la persona modificada y el ID del usuario logueado
	$stmt = $conexionBD->prepare('UPDATE Persona SET cNombre = ?, cEmail = ?, cWebsite = ?, cComentario = ?, cSexo = ? WHERE cId = ? AND cIdUsuario = ?');
    
    $okFlag = false;
    if ($stmt) {
        // 2. Vincular los parámetros. 
        $stmt->bind_param("sssssii", $nombre, $email, $website, $comentario, $sexo, $idPersona, $idUsuario);

        // 3. Ejecutar la actualización capturando posibles excepciones de índices duplicados
        try {
            $okFlag = $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            $okFlag = false;
        }

        // 4. Cerrar y liberar la sentencia
        $stmt->close();
    }

    if ($okFlag) {
        return OK;
    } else {
        return ERROR_MODIFICA_PERSONA;
    }
}

/**
 * Función PHP que encapsula el borrado seguro de una persona existente a través de su ID.
 */
function borrarPersona($conexionBD, $idPersona, $idUsuario) {
    // 1. Preparar la sentencia de borrado directamente desde el objeto de conexión
    $stmt = $conexionBD->prepare('DELETE FROM Persona WHERE cId = ? AND cIdUsuario = ?');
    
    $okFlag = false;
    if ($stmt) {
        // 2. Vincular el parámetro del ID del contacto/persona borrado y el ID del usuario logueado
        $stmt->bind_param("ii", $idPersona, $idUsuario);

        // 3. Ejecutar la acción en el servidor
        try {
            $okFlag = $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            $okFlag = false;
        }

        // 4. Cerrar y liberar la sentencia
        $stmt->close();
    }

    if ($okFlag) {
        return OK;
    } else {
        return ERROR_BORRADO_PERSONA;
    }
}
?>


