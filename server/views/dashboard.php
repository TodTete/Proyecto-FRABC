<?php
require_once __DIR__ . '/../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../controllers/DocumentController.php';
require_once __DIR__ . '/../controllers/NotificationController.php';
$usuario = AuthMiddleware::getUser();
$documentController = new DocumentController();
$notificationController = new NotificationController();

// Obtener documentos recientes
if ($usuario['rol'] === 'admin') {
    $documentos = $documentController->obtenerDocumentosRecientes();
} else {
    $documentos = $documentController->obtenerDocumentosRecientes($usuario['id']);
}

// Obtener notificaciones no leídas
$notificaciones_no_leidas = $notificationController->contarNotificacionesNoLeidas($usuario['id']);

// Estadísticas
$stats = $documentController->obtenerEstadisticas($usuario['id'], $usuario['rol']);

$base_url = '/project/public';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - UTTECAM</title>
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
            animation: slideDown 0.5s ease-out;
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
        
        .notification-badge {
            position: relative;
            cursor: pointer;
        }
        
        .notification-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ff4757;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            animation: pulse 2s infinite;
        }
        
        .role-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
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
        
        .nav {
            background: white;
            padding: 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            border-bottom: 3px solid #4a7c59;
            animation: slideDown 0.6s ease-out;
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
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 2rem;
            animation: fadeInUp 0.7s ease-out;
        }
        
        .welcome-section {
            background: linear-gradient(135deg, #4a7c59 0%, #5a8c69 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 5px 20px rgba(74, 124, 89, 0.2);
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-left: 4px solid #4a7c59;
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-3px);
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #4a7c59;
        }
        
        .stat-label {
            color: #666;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }
        
        .documents-section {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        
        .section-header {
            background: linear-gradient(135deg, #4a7c59 0%, #5a8c69 100%);
            color: white;
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #ff8c42 0%, #ff7b2e 100%);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .documents-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .documents-table th,
        .documents-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e1e5e9;
        }
        
        .documents-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
        }
        
        .documents-table tbody tr:hover {
            background: #f8f9fa;
        }
        
        .folio-highlight {
            background: linear-gradient(135deg, #4a7c59 0%, #5a8c69 100%);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-weight: bold;
            font-size: 0.9rem;
        }
        
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-pendiente { background: #fff3cd; color: #856404; }
        .status-proceso { background: #d1ecf1; color: #0c5460; }
        .status-atendido { background: #d4edda; color: #155724; }
        
        .urgencia-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .urgencia-ordinario { background: #f8f9fa; color: #666; }
        .urgencia-urgente { background: #f8d7da; color: #721c24; }
        
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-edit {
            background: #17a2b8;
            color: white;
        }
        
        .btn-view {
            background: #28a745;
            color: white;
        }
        
        .btn-download {
            background: #6c757d;
            color: white;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #666;
        }
        
        .footer {
            background: #6c757d;
            color: white;
            text-align: center;
            padding: 1.5rem;
            margin-top: 3rem;
        }
        
        /* Animaciones */
        @keyframes slideDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        @keyframes fadeInUp {
            from {
                transform: translateY(30px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .documents-table {
                font-size: 0.8rem;
            }
            
            .documents-table th,
            .documents-table td {
                padding: 0.5rem;
            }
            
            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <div class="logo">UTTECAM</div>
            <div class="page-title">Panel de Control</div>
        </div>
        <div class="user-info">
            <div class="notification-badge" onclick="window.location.href='<?php echo $base_url; ?>/notificaciones'">
                🔔
                <?php if ($notificaciones_no_leidas > 0): ?>
                    <span class="notification-count"><?php echo $notificaciones_no_leidas; ?></span>
                <?php endif; ?>
            </div>
            <span class="user-name">Bienvenido, <?php echo htmlspecialchars($usuario['nombre']); ?></span>
            <span class="role-badge"><?php echo ucfirst($usuario['rol']); ?></span>
            <form action="<?php echo $base_url; ?>/logout" method="POST" style="display: inline;">
                <button type="submit" class="btn-logout">Cerrar Sesión</button>
            </form>
        </div>
    </div>
    
    <div class="nav">
        <ul>
            <li><a href="<?php echo $base_url; ?>/dashboard" class="active">Dashboard</a></li>
            <li><a href="<?php echo $base_url; ?>/documentos">Documentos</a></li>
            <li><a href="<?php echo $base_url; ?>/notificaciones">Notificaciones</a></li>
            <?php if ($usuario['rol'] === 'admin'): ?>
                <li><a href="<?php echo $base_url; ?>/usuarios">Usuarios</a></li>
            <?php endif; ?>
            <li><a href="<?php echo $base_url; ?>/perfil">Perfil</a></li>
        </ul>
    </div>
    
    <div class="container">
        <div class="welcome-section">
            <h1>¡Bienvenido al Sistema de Documentos!</h1>
            <p>Gestiona tus memorandos de manera eficiente y mantente al día con las notificaciones.</p>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['total']; ?></div>
                <div class="stat-label">Total de Documentos</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['pendientes']; ?></div>
                <div class="stat-label">Pendientes</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['proceso']; ?></div>
                <div class="stat-label">En Proceso</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['atendidos']; ?></div>
                <div class="stat-label">Atendidos</div>
            </div>
        </div>
        
        <div class="documents-section">
            <div class="section-header">
                <h2>Memorandos Recientes</h2>
                <a href="<?php echo $base_url; ?>/subir-documento" class="btn-primary">📄 Nuevo Documento</a>
            </div>
            
            <?php if (empty($documentos)): ?>
                <div class="empty-state">
                    <h3>No hay documentos recientes</h3>
                    <p>Los documentos aparecerán aquí una vez que se creen</p>
                </div>
            <?php else: ?>
                <table class="documents-table">
                    <thead>
                        <tr>
                            <th>Folio</th>
                            <th>Fecha Documento</th>
                            <th>Remitente</th>
                            <th>Destinatario</th>
                            <th>Urgencia</th>
                            <th>Estado</th>
                            <th>Fecha Límite</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($documentos as $doc): ?>
                            <tr>
                                <td>
                                    <span class="folio-highlight"><?php echo htmlspecialchars($doc['folio']); ?></span>
                                </td>
                                <td><?php echo $doc['fecha_documento'] ? date('d/m/Y', strtotime($doc['fecha_documento'])) : '-'; ?></td>
                                <td><?php echo htmlspecialchars($doc['remitente_nombre']); ?></td>
                                <td><?php echo htmlspecialchars($doc['destinatario_nombre']); ?></td>
                                <td>
                                    <span class="urgencia-badge urgencia-<?php echo $doc['urgencia']; ?>">
                                        <?php echo ucfirst($doc['urgencia']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge status-<?php echo $doc['estatus_atencion']; ?>">
                                        <?php echo ucfirst($doc['estatus_atencion']); ?>
                                    </span>
                                </td>
                                <td><?php echo $doc['fecha_limite'] ? date('d/m/Y', strtotime($doc['fecha_limite'])) : '-'; ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-sm btn-edit" onclick="editDocument(<?php echo $doc['id']; ?>)">✏️</button>
                                        <?php if ($doc['documento_blob']): ?>
                                            <button class="btn-sm btn-view" onclick="viewPDF(<?php echo $doc['id']; ?>)">👁️</button>
                                            <button class="btn-sm btn-download" onclick="downloadPDF(<?php echo $doc['id']; ?>)">⬇️</button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="footer">
        © 2025 UTTECAM. Todos los derechos reservados.
    </div>
    
    <script>
        function editDocument(id) {
            window.location.href = '<?php echo $base_url; ?>/editar-documento/' + id;
        }
        
        function viewPDF(id) {
            window.open('<?php echo $base_url; ?>/ver-pdf/' + id, '_blank');
        }
        
        function downloadPDF(id) {
            window.location.href = '<?php echo $base_url; ?>/descargar-pdf/' + id;
        }
        
        // Actualizar notificaciones cada 30 segundos
        setInterval(function() {
            fetch('<?php echo $base_url; ?>/api/notificaciones-count')
                .then(response => response.json())
                .then(data => {
                    const badge = document.querySelector('.notification-count');
                    if (data.count > 0) {
                        if (badge) {
                            badge.textContent = data.count;
                        } else {
                            const notificationBadge = document.querySelector('.notification-badge');
                            const countElement = document.createElement('span');
                            countElement.className = 'notification-count';
                            countElement.textContent = data.count;
                            notificationBadge.appendChild(countElement);
                        }
                    } else if (badge) {
                        badge.remove();
                    }
                })
                .catch(error => console.log('Error actualizando notificaciones:', error));
        }, 30000);
    </script>
</body>
</html>
