<?php
require_once __DIR__ . '/../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../controllers/DocumentController.php';
$usuario = AuthMiddleware::getUser();
$documentController = new DocumentController();

// Obtener filtros
$busqueda = $_GET['busqueda'] ?? '';
$estado = $_GET['estado'] ?? '';
$urgencia = $_GET['urgencia'] ?? '';

// Obtener documentos
if ($usuario['rol'] === 'admin') {
    $documentos = $documentController->obtenerDocumentos();
} else {
    $documentos = $documentController->obtenerDocumentos($usuario['id']);
}

// Aplicar filtros
if (!empty($busqueda) || !empty($estado) || !empty($urgencia)) {
    $documentos = array_filter($documentos, function($doc) use ($busqueda, $estado, $urgencia) {
        $matchBusqueda = empty($busqueda) || 
            stripos($doc['folio'], $busqueda) !== false ||
            stripos($doc['contenido'], $busqueda) !== false ||
            stripos($doc['remitente_nombre'], $busqueda) !== false ||
            stripos($doc['destinatario_nombre'], $busqueda) !== false;
        
        $matchEstado = empty($estado) || $doc['estatus_atencion'] === $estado;
        $matchUrgencia = empty($urgencia) || $doc['urgencia'] === $urgencia;
        
        return $matchBusqueda && $matchEstado && $matchUrgencia;
    });
}

$base_url = '/project/public';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentos - UTTECAM</title>
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
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
            animation: fadeInUp 0.7s ease-out;
        }
        
        .search-bar {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }
        
        .search-form {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr auto;
            gap: 1rem;
            align-items: end;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
        }
        
        .form-group label {
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .form-input,
        .form-select {
            padding: 0.75rem;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }
        
        .form-input:focus,
        .form-select:focus {
            outline: none;
            border-color: #4a7c59;
            background: white;
            box-shadow: 0 0 0 3px rgba(74, 124, 89, 0.1);
        }
        
        .btn-search {
            background: linear-gradient(135deg, #4a7c59 0%, #5a8c69 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            height: fit-content;
        }
        
        .btn-search:hover {
            background: linear-gradient(135deg, #5a8c69 0%, #6a9c79 100%);
            transform: translateY(-2px);
        }
        
        .actions-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #ff8c42 0%, #ff7b2e 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #ff7b2e 0%, #ff6a1a 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 140, 66, 0.3);
        }
        
        .documents-grid {
            display: grid;
            gap: 1.5rem;
        }
        
        .document-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-left: 4px solid #4a7c59;
            transition: all 0.3s ease;
            animation: fadeIn 0.8s ease-out;
        }
        
        .document-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .document-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }
        
        .document-folio {
            font-size: 1.2rem;
            font-weight: bold;
            color: white;
            background: linear-gradient(135deg, #4a7c59 0%, #5a8c69 100%);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            display: inline-block;
        }
        
        .document-status {
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
            margin-top: 1rem;
        }
        
        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
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
        
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            z-index: 9999;
            animation: slideInRight 0.5s ease-out;
            max-width: 300px;
        }
        
        .toast-success {
            background: linear-gradient(135deg, #51cf66 0%, #40c057 100%);
        }
        
        .toast-error {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
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
        
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .search-form {
                grid-template-columns: 1fr;
            }
            
            .actions-bar {
                flex-direction: column;
                gap: 1rem;
            }
            
            .document-header {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .document-meta {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <div class="logo">UTTECAM</div>
            <div class="page-title">Gestión de Documentos</div>
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
            <li><a href="<?php echo $base_url; ?>/documentos" class="active">Documentos</a></li>
            <li><a href="<?php echo $base_url; ?>/notificaciones">Notificaciones</a></li>
            <?php if ($usuario['rol'] === 'admin'): ?>
                <li><a href="<?php echo $base_url; ?>/usuarios">Usuarios</a></li>
            <?php endif; ?>
            <li><a href="<?php echo $base_url; ?>/perfil">Perfil</a></li>
        </ul>
    </div>
    
    <div class="container">
        <?php if (isset($_GET['error'])): ?>
            <div class="error-message">
                ⚠ <?php echo htmlspecialchars(urldecode($_GET['error'])); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="success-message">
                ✓ <?php echo htmlspecialchars(urldecode($_GET['success'])); ?>
            </div>
        <?php endif; ?>
        
        <div class="search-bar">
            <form method="GET" class="search-form">
                <div class="form-group">
                    <label for="busqueda">Buscar documentos</label>
                    <input type="text" id="busqueda" name="busqueda" class="form-input" 
                           placeholder="Folio, contenido, remitente..." value="<?php echo htmlspecialchars($busqueda); ?>">
                </div>
                
                <div class="form-group">
                    <label for="estado">Estado</label>
                    <select id="estado" name="estado" class="form-select">
                        <option value="">Todos los estados</option>
                        <option value="pendiente" <?php echo $estado === 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                        <option value="proceso" <?php echo $estado === 'proceso' ? 'selected' : ''; ?>>En Proceso</option>
                        <option value="atendido" <?php echo $estado === 'atendido' ? 'selected' : ''; ?>>Atendido</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="urgencia">Urgencia</label>
                    <select id="urgencia" name="urgencia" class="form-select">
                        <option value="">Todas</option>
                        <option value="ordinario" <?php echo $urgencia === 'ordinario' ? 'selected' : ''; ?>>Ordinario</option>
                        <option value="urgente" <?php echo $urgencia === 'urgente' ? 'selected' : ''; ?>>Urgente</option>
                    </select>
                </div>
                
                <button type="submit" class="btn-search">🔍 Buscar</button>
            </form>
        </div>
        
        <div class="actions-bar">
            <h2 style="color: #333;">
                Documentos 
                <small style="color: #666; font-weight: normal;">(<?php echo count($documentos); ?> encontrados)</small>
            </h2>
            <a href="<?php echo $base_url; ?>/subir-documento" class="btn-primary">
                📄 Subir Documento
            </a>
        </div>
        
        <div class="documents-grid">
            <?php if (empty($documentos)): ?>
                <div class="empty-state">
                    <div class="empty-state-icon">📄</div>
                    <h3>No se encontraron documentos</h3>
                    <p>No hay documentos que coincidan con los criterios de búsqueda</p>
                </div>
            <?php else: ?>
                <?php foreach ($documentos as $doc): ?>
                    <div class="document-card">
                        <div class="document-header">
                            <div class="document-folio"><?php echo htmlspecialchars($doc['folio']); ?></div>
                            <div>
                                <span class="document-status status-<?php echo $doc['estatus_atencion']; ?>">
                                    <?php echo ucfirst($doc['estatus_atencion']); ?>
                                </span>
                                <span class="urgencia-badge urgencia-<?php echo $doc['urgencia']; ?>">
                                    <?php echo ucfirst($doc['urgencia']); ?>
                                </span>
                            </div>
                        </div>
                        
                        <div>
                            <p><strong>Fecha:</strong> <?php echo date('d/m/Y', strtotime($doc['fecha_documento'] ?? $doc['fecha_creacion'])); ?></p>
                            <p><strong>Remitente:</strong> <?php echo htmlspecialchars($doc['remitente_nombre']); ?></p>
                            <p><strong>Destinatario:</strong> <?php echo htmlspecialchars($doc['destinatario_nombre']); ?></p>
                            <p><strong>Área:</strong> <?php echo htmlspecialchars($doc['area_nombre']); ?> → <?php echo htmlspecialchars($doc['area_destino_nombre'] ?? 'N/A'); ?></p>
                            <?php if ($doc['fecha_limite']): ?>
                                <p><strong>Fecha Límite:</strong> <?php echo date('d/m/Y', strtotime($doc['fecha_limite'])); ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <div style="margin-top: 1rem;">
                            <p><strong>Contenido:</strong></p>
                            <p><?php echo nl2br(htmlspecialchars(substr($doc['contenido'], 0, 200))); ?>
                            <?php if (strlen($doc['contenido']) > 200): ?>...</p><?php endif; ?>
                        </div>
                        
                        <div class="action-buttons">
                            <a href="<?php echo $base_url; ?>/editar-documento/<?php echo $doc['id']; ?>" class="btn-sm btn-edit">✏️ Editar</a>
                            <?php if ($doc['documento_blob']): ?>
                                <a href="<?php echo $base_url; ?>/ver-pdf/<?php echo $doc['id']; ?>" target="_blank" class="btn-sm btn-view">👁️ Ver PDF</a>
                                <a href="<?php echo $base_url; ?>/descargar-pdf/<?php echo $doc['id']; ?>" class="btn-sm btn-download">⬇️ Descargar PDF</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="footer">
        © 2025 UTTECAM. Todos los derechos reservados.
    </div>
    
    <script>
        // Función para mostrar notificaciones toast
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.textContent = message;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 500);
            }, 3000);
        }
        
        <?php if (isset($_GET['error'])): ?>
            showToast('<?php echo htmlspecialchars(urldecode($_GET['error'])); ?>', 'error');
        <?php endif; ?>
        
        <?php if (isset($_GET['success'])): ?>
            showToast('<?php echo htmlspecialchars(urldecode($_GET['success'])); ?>', 'success');
        <?php endif; ?>
    </script>
</body>
</html>
