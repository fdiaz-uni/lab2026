<?php
/**
 * inc/login.php
 * Componente que presenta el formulario de login para un usuario.
 */
$erroresLogin = $_SESSION['errores_login'] ?? [];
$emailPrevio  = $_SESSION['datos_login']['email'] ?? "";

unset($_SESSION['errores_login']);
unset($_SESSION['datos_login']);
?>

<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="well">
                <h3>Iniciar Sesión</h3>
                <form action="<?= RAIZ_URL ?>src/controladores/usuario.php" method="post" id="formLogin">
                    
                    <div class="form-group <?= isset($erroresLogin['email']) ? 'has-error' : '' ?>">
                        <label for="fEmail" class="control-label">Correo electrónico:</label>
                        <input type="email" name="fEmail" id="fEmail" class="form-control" 
                               value="<?= htmlspecialchars($emailPrevio, ENT_QUOTES, 'UTF-8') ?>" required>
                        <?php if (isset($erroresLogin['email'])): ?>
                            <span class="help-block"><?= $erroresLogin['email'] ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group <?= isset($erroresLogin['password']) ? 'has-error' : '' ?>">
                        <label for="fPassword" class="control-label">Contraseña:</label>
                        <input type="password" name="fPassword" id="fPassword" class="form-control" required minlength="4">
                        <?php if (isset($erroresLogin['password'])): ?>
                            <span class="help-block"><?= $erroresLogin['password'] ?></span>
                        <?php endif; ?>
                    </div>

                    <input type="hidden" name="login" value="1">
                    <button type="submit" class="btn btn-primary btn-block">Entrar</button>
                </form>
            </div>
        </div>
    </div>
</div>
