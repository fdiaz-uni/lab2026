<?php
/**
 * inc/modelo-persona-pdo.php
 * Modelo de datos para la entidad Persona (API PDO).
 */

/**
 * Función PHP que encapsula la inserción segura de una nueva persona.
 * Utiliza Consultas Preparadas (Prepared Statements) para blindar el sistema ante SQLi (inyección de código SQL).
 */
/* Función PHP que encapsula la inserción de una nueva persona */
function nuevaPersona($conexionBD, $nombre, $email, $website, $comentario, $sexo) {
    // Preparación de la consulta de inserción nueva persona
    $sentenciaSQL = 'INSERT INTO Persona (cNombre, cEmail, cWebsite, cComentario, cSexo) VALUES (?, ?, ?, ?, ?)';
    
    try {
        $stmt = $conexionBD->prepare($sentenciaSQL);
        $stmt->execute([$nombre, $email, $website, $comentario, $sexo]);
        return OK;
    } catch (PDOException $e) {
        return ERROR_ALTA_PERSONA;
    }
}

/**
 * CONTRAEJEMPLO DIDÁCTICO - LO QUE NO HAY QUE HACER!!!
 * Función que emula la inserción de una nueva persona de forma INSEGURA.
 * Muestra el peligro de concatenar variables directamente en la cadena SQL.
 * function nuevaPersona_vInsegura($conexionBD, $nombre, $email, $website, $comentario, $sexo) {
 * 	// Define la query directamente
 *	$sentenciaSQL = "INSERT INTO Persona (cNombre, cEmail, cWebsite, cComentario, cSexo) VALUES ('$nombre', '$email', '$website', '$comentario', '$sexo')";
 *  
 *	try {
 *		$conexionBD->exec($sentenciaSQL);
 *		return OK;
 *	} catch (PDOException $e) {
 *		return ERROR_ALTA_PERSONA;
 * 	}
 * }
 */


/**
 * Función PHP que encapsula la consulta segura de una persona por su ID.
 */
function getPersona($conexionBD, $id) {
    // Preparación de la consulta select persona
    $sentenciaSQL = 'SELECT cNombre, cEmail, cWebsite, cComentario, cSexo FROM Persona WHERE cId = ?';
    
    try {
        $stmt = $conexionBD->prepare($sentenciaSQL);
        $stmt->execute([$id]);
        
        // Obtener el resultado de la consulta
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        return $fila ? $fila : [];
    } catch (PDOException $e) {
        return ERROR_CONSULTA_PERSONA;
    }
}

/**
 * Función PHP que encapsula la obtención de todo el listado de personas.
 */
function getPersonas($conexionBD, $sentenciaSQL = "SELECT * FROM Persona") {
    // Preparación de la consulta select todas las personas
    $stmt = $conexionBD->prepare($sentenciaSQL);
    $stmt->execute();

    // Almacenamiento de todas las filas en un array
    $array_filas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $array_filas;
}	
?>