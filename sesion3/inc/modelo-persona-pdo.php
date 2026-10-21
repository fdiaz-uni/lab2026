<?php

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

/* Función PHP que encapsula la inserción de una nueva persona. 
Versión insegura: REQUIERE SANEAR DATOS DE ENTRADA CON mysqli_real_escape_string() */
function nuevaPersona_vInsegura($conexionBD, $nombre, $email, $website, $comentario, $sexo) {
    // Define la query directamente
    $sentenciaSQL = "INSERT INTO Persona (cNombre, cEmail, cWebsite, cComentario, cSexo) VALUES ('$nombre', '$email', '$website', '$comentario', '$sexo')";
    
    try {
        $conexionBD->exec($sentenciaSQL);
        return OK;
    } catch (PDOException $e) {
        return ERROR_ALTA_PERSONA;
    }
}



/* Función PHP que encapsula la consulta de una persona por id */
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

/* Función PHP que encapsula la consulta de todas las personas */
function getPersonas($conexionBD, $sentenciaSQL = "SELECT * FROM Persona") {
    // Preparación de la consulta select todas las personas
    $stmt = $conexionBD->prepare($sentenciaSQL);
    $stmt->execute();

    // Almacenamiento de todas las filas en un array
    $array_filas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $array_filas;
}

/* Función PHP que encapsula la actualización de una persona existente */
function modificarPersona($conexionBD, $id, $nombre, $email, $website, $comentario, $sexo) {
    // Preparación de la consulta de actualización
    $sentenciaSQL = 'UPDATE Persona SET cNombre = ?, cEmail = ?, cWebsite = ?, cComentario = ?, cSexo = ? WHERE cId = ?';
    
    try {
        $stmt = $conexionBD->prepare($sentenciaSQL);
        // Ejecutar la consulta con los parámetros proporcionados
        $stmt->execute([$nombre, $email, $website, $comentario, $sexo, $id]);
        return OK;
    } catch (PDOException $e) {
        return ERROR_MODIFICA_PERSONA;
    }
}

/* Función PHP que encapsula el borrado de una persona existente */
function borrarPersona($conexionBD, $id) {
    // Preparación de la consulta de borrado
    $sentenciaSQL = 'DELETE FROM Persona WHERE cId = ?';
    
    try {
        $stmt = $conexionBD->prepare($sentenciaSQL);
        // Ejecutar la consulta con el parámetro proporcionado
        $stmt->execute([$id]);
        return OK;
    } catch (PDOException $e) {
        return ERROR_BORRADO_PERSONA;
    }
}

?>