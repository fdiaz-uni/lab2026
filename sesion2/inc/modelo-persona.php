
<?php
/**
 * inc/modelo-persona.php
 * Modelo de datos para la entidad Persona (API MySQLi Orientada a Objetos).
 */

/**
 * Función PHP que encapsula la inserción segura de una nueva persona.
 * Utiliza Consultas Preparadas (Prepared Statements) para blindar el sistema ante SQLi (inyección de código SQL).
 */
function nuevaPersona($conexionBD, $nombre, $email, $website, $comentario, $sexo) {
    // 1. Preparar la sentencia directamente desde el objeto de conexión
    $stmt = $conexionBD->prepare('INSERT INTO Persona (cNombre, cEmail, cWebsite, cComentario, cSexo) VALUES (?, ?, ?, ?, ?)');
    
    $okFlag = false;
    if ($stmt) {
        // 2. Vincular los parámetros por referencia (s = string)
        $stmt->bind_param("sssss", $nombre, $email, $website, $comentario, $sexo);

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
function getPersona($conexionBD, $id) {
    // 1. Preparar la sentencia de selección
    $stmt = $conexionBD->prepare('SELECT cNombre, cEmail, cWebsite, cComentario, cSexo FROM Persona WHERE cId = ?');
    
    $fila = array();
    $okFlag = false;
    if ($stmt) {
        // 2. Vincular el parámetro del ID (i = entero)
        $stmt->bind_param("i", $id);

        // 3. Ejecutar la consulta
        $okFlag = $stmt->execute();
        
        // 4. Obtener el buffer de resultados nativo
        $resultado = $stmt->get_result();
        
        if ($resultado && $resultado->num_rows > 0) {
            // Extraemos la fila como un array asociativo
            $fila = $resultado->fetch_assoc();
        }
        
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
function getPersonas($conexionBD, $sentenciaSQL = "SELECT cId, cNombre, cEmail FROM Persona") {
    // Ejecutar la consulta directa utilizando el método query() del objeto de conexión
    $resultado = $conexionBD->query($sentenciaSQL);

    $array_filas = array();
    if ($resultado) {
        // Almacenamiento de todas las filas en el array asociativo
        while ($fila = $resultado->fetch_assoc()) {
            $array_filas[] = $fila; // Equivalente nativo optimizado a array_push($array_filas, $fila)
        }
        
        // Liberar la memoria asociada al resultado
        $resultado->free();
    }
    
    return $array_filas;
}

?>