<?php
/**
 * src/modelos/usuario.php
 */
function autenticarUsuario($conexionBD, $email, $password) {
    $stmt = $conexionBD->prepare('SELECT cId, cNombre, cEmail, cPassword FROM Usuario WHERE cEmail = ?');
    
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        if ($resultado && $resultado->num_rows === 1) {
            $usuario = $resultado->fetch_assoc();
            $stmt->close();
            
            // Comparación en texto plano (Nota: deberá hashearse la constraseña y comparar con la hasheada, por ejemplo con password_verify())
            if ($password === $usuario['cPassword']) {
                // Se elimina la contraseña del array antes de devolverlo (al controlador), por seguridad
                unset($usuario['cPassword']);
                return $usuario;
            }
        } else {
            $stmt->close();
        }
    }
    return false;
}
?>
