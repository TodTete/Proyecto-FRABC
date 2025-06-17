<?php
require_once __DIR__ . '/../middleware/AuthMiddleware.php';
$usuario = AuthMiddleware::getUser();
$base_url = '/project/public';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - UTTECAM</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            min-height: 100vh;
        }
        
        .header {
            background: linear-gradient(135deg, #4a7c59 0%, #5a8c69 100%);
            padding: 1rem 2rem;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header-left {
            display: flex;
            align-items: center;
            gap: 2rem;
        }
        
        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            font-style: italic;
            letter-spacing: 1px;
        }
        
        .page-title {
            font-size: 1.2rem;
            font-weight: 500;
            opacity: 0.9;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .user-name {
            font-size: 0.95rem;
            opacity: 0.9;
        }
        
        .btn-logout {
            background: linear-gradient(135deg, #ff8c42 0%, #ff7b2e 100%);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-logout:hover {
            background: linear-gradient(135deg, #ff7b2e 0%, #ff6a1a 100%);
            transform: translateY(-1px);
        }
        
        .nav {
            background: white;
            padding: 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            border-bottom: 3px solid #4a7c59;
        }
        
        .nav ul {
            list-style: none;
            display: flex;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .nav li {
            flex: 1;
        }
        
        .nav a {
            display: block;
            text-decoration: none;
            color: #4a7c59;
            font-weight: 600;
            padding: 1rem 2rem;
            text-align: center;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
        }
        
        .nav a:hover,
        .nav a.active {
            background: #f8f9fa;
            border-bottom-color: #ff8c42;
            color: #333;
        }
        
        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        
        .card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            border-top: 4px solid #4a7c59;
        }
        
        .profile-header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 2px solid #f8f9fa;
        }
        
        .profile-avatar {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #4a7c59 0%, #5a8c69 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 2.5rem;
            color: white;
        }
        
        .profile-info {
            display: grid;
            gap: 1rem;
        }
        
        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 10px;
            border-left: 4px solid #4a7c59;
        }
        
        .info-label {
            font-weight: 600;
            color: #333;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .info-value {
            color: #666;
            font-weight: 500;
        }
        
        .role-badge {
            background: linear-gradient(135deg, #ff8c42 0%, #ff7b2e 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .footer {
            background: #6c757d;
            color: white;
            text-align: center;
            padding: 1.5rem;
            margin-top: 3rem;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            
            .header-left {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .nav ul {
                flex-direction: column;
            }
            
            .container {
                padding: 0 1rem;
            }
            
            .info-item {
                flex-direction: column;
                gap: 0.5rem;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <div class="logo">UTTECAM</div>
            <div class="page-title">Perfil Personal</div>
        </div>
        <div class="user-info">
            <span class="user-name">Bienvenido, <?php echo htmlspecialchars($usuario['nombre']); ?></span>
            <form action="<?php echo $base_url; ?>/logout" method="POST" style="display: inline;">
                <button type="submit" class="btn-logout">Cerrar Sesión</button>
            </form>
        </div>
    </div>
    
    <div class="nav">
        <ul>
            <li><a href="<?php echo $base_url; ?>/dashboard">Dashboard</a></li>
            <li><a href="<?php echo $base_url; ?>/documentos">Documentos</a></li>
            <li><a href="<?php echo $base_url; ?>/notificaciones">Notificaciones</a></li>
            <?php if ($usuario['rol'] === 'admin'): ?>
                <li><a href="<?php echo $base_url; ?>/usuarios">Usuarios</a></li>
            <?php endif; ?>
            <li><a href="<?php echo $base_url; ?>/perfil" class="active">Perfil</a></li>
        </ul>
    </div>
    
    <div class="container">
        <div class="card">
            <div class="profile-header">
                <div class="profile-avatar">
                    👤
                </div>
                <h2 style="color: #333; margin-bottom: 0.5rem;"><?php echo htmlspecialchars($usuario['nombre']); ?></h2>
                <p style="color: #666;">Información de tu cuenta personal</p>
            </div>
            
            <div class="profile-info">
                <div class="info-item">
                    <span class="info-label">
                        👤 Nombre Completo:
                    </span>
                    <span class="info-value"><?php echo htmlspecialchars($usuario['nombre']); ?></span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">
                        ✉️ Correo Electrónico:
                    </span>
                    <span class="info-value"><?php echo htmlspecialchars($usuario['correo']); ?></span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">
                        🏷️ Rol del Sistema:
                    </span>
                    <span class="role-badge"><?php echo ucfirst($usuario['rol']); ?></span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">
                        🆔 ID de Usuario:
                    </span>
                    <span class="info-value">#<?php echo str_pad($usuario['id'], 6, '0', STR_PAD_LEFT); ?></span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="footer">
        © 2025 UTTECAM. Todos los derechos reservados.
    </div>
</body>
</html>
