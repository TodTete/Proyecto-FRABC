<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Iniciar Sesión - UTTECAM</title>
        <link rel="stylesheet" href="/project/server/views/styles/var.css">
        <link rel="stylesheet" href="/project/server/views/styles/main.css">
        <link rel="stylesheet" href="/project/server/views/styles/login.css">
    </head>
    <body>
    <?php require_once __DIR__ . '/components/header.html'; ?>
    <div class="main-container">
        <div class="image-section" style="padding:0;">
            <img src="/project/public/images/fondo.jpg" alt="Fondo UTTECAM" style="width:100%; height:100%; object-fit:cover; border-top-right-radius:10px; border-bottom-right-radius:10px; display:block;">
        </div>
        <div class="login-section">
            <div class="login-card">
                <div class="login-header">
                    <h2>Iniciar Sesión</h2>
                    <p>Completa los campos para acceder a tu cuenta</p>
                </div>
                
                <div class="login-form">
                    <?php if (isset($_GET['error'])): ?>
                        <div class="error-message">
                            <span>⚠</span>
                            <?php echo htmlspecialchars(urldecode($_GET['error'])); ?>
                        </div>
                    <?php endif; ?>

                    <form action="/project/public/login" method="POST">
                        <div class="form-group">
                            <label for="correo">Ingresa tu correo</label>
                            <div class="input-container">
                                <span class="input-icon">✉</span>
                                <input 
                                    type="email" 
                                    id="correo" 
                                    name="correo" 
                                    class="form-input"
                                    placeholder="example@correo.com"
                                    required
                                >
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="contraseña">Ingresa tu contraseña</label>
                            <div class="input-container">
                                <span class="input-icon">🔒</span>
                                <input 
                                    type="password" 
                                    id="contraseña" 
                                    name="contraseña" 
                                    class="form-input"
                                    placeholder="••••••••"
                                    required
                                >
                            </div>
                        </div>

                        <button type="submit" class="btn-login">Iniciar Sesión</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
<?php require_once __DIR__ . '/components/footer.html'; ?>
</body>
</html>