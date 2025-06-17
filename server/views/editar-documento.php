<?php
require_once __DIR__ . '/../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../controllers/DocumentController.php';
AuthMiddleware::requireAuth();

$documento_id = $_GET['id'] ?? 0;
$documentController = new DocumentController();
$documento = $documentController->obtenerDocumentoPorId($documento_id);

if (!$documento) {
    header("Location: /project/public/documentos?error=" . urlencode("Documento no encontrado"));
    exit();
}

$usuario = AuthMiddleware::getUser();
$usuarios = $documentController->obtenerUsuarios();
$areas = $documentController->obtenerAreas();
$base_url = '/project/public';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Documento - UTTECAM</title>
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
        
        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 2rem;
            animation: fadeInUp 0.7s ease-out;
        }
        
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #4a7c59;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }
        
        .back-btn:hover {
            color: #ff8c42;
            transform: translateX(-5px);
        }
        
        .card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            border-top: 4px solid #4a7c59;
        }
        
        .form-header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f8f9fa;
        }
        
        .folio-highlight {
            background: linear-gradient(135deg, #4a7c59 0%, #5a8c69 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: bold;
            font-size: 1.1rem;
            display: inline-block;
            margin-bottom: 0.5rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
            animation: fadeIn 0.8s ease-out;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 600;
        }
        
        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }
        
        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: #4a7c59;
            background: white;
            box-shadow: 0 0 0 3px rgba(74, 124, 89, 0.1);
        }
        
        .form-textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        
        .btn-submit {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #4a7c59 0%, #5a8c69 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }
        
        .btn-submit:hover {
            background: linear-gradient(135deg, #5a8c69 0%, #6a9c79 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(74, 124, 89, 0.3);
        }
        
        .error-message,
        .success-message {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-weight: 500;
            animation: slideInDown 0.5s ease-out;
        }
        
        .error-message {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            color: white;
        }
        
        .success-message {
            background: linear-gradient(135deg, #51cf66 0%, #40c057 100%);
            color: white;
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
        
        @keyframes slideInDown {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .container {
                padding: 0 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <div class="logo">UTTECAM</div>
            <div class="page-title">Editar Documento</div>
        </div>
        <div class="user-info">
            <span class="user-name">Bienvenido, <?php echo htmlspecialchars($usuario['nombre']); ?></span>
            <form action="<?php echo $base_url; ?>/logout" method="POST" style="display: inline;">
                <button type="submit" class="btn-logout">Cerrar Sesión</button>
            </form>
        </div>
    </div>
    
    <div class="container">
        <a href="<?php echo $base_url; ?>/documentos" class="back-btn">
            ← Volver a Documentos
        </a>
        
        <div class="card">
            <div class="form-header">
                <div class="folio-highlight"><?php echo htmlspecialchars($documento['folio']); ?></div>
                <h2 style="color: #333;">Editar Documento</h2>
                <p style="color: #666;">Modifica los campos necesarios del memorando</p>
            </div>
            
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
            
            <form action="<?php echo $base_url; ?>/actualizar-documento" method="POST">
                <input type="hidden" name="id" value="<?php echo $documento['id']; ?>">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="folio">Folio *</label>
                        <input type="text" id="folio" name="folio" class="form-input" required 
                               value="<?php echo htmlspecialchars($documento['folio']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="fecha_documento">Fecha del Documento</label>
                        <input type="date" id="fecha_documento" name="fecha_documento" class="form-input" 
                               value="<?php echo $documento['fecha_documento']; ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="entidad_productora">Entidad Productora</label>
                    <input type="text" id="entidad_productora" name="entidad_productora" class="form-input" 
                           placeholder="Ej: Dirección General" value="<?php echo htmlspecialchars($documento['entidad_productora']); ?>">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="destinatario_id">Destinatario *</label>
                        <select id="destinatario_id" name="destinatario_id" class="form-select" required>
                            <option value="">Seleccionar destinatario</option>
                            <?php foreach ($usuarios as $user): ?>
                                <option value="<?php echo $user['id']; ?>" 
                                        <?php echo $user['id'] == $documento['destinatario_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($user['nombre'] . ' (' . $user['correo'] . ')'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="area_destino_id">Área Destino</label>
                        <select id="area_destino_id" name="area_destino_id" class="form-select">
                            <option value="">Seleccionar área destino</option>
                            <?php foreach ($areas as $area): ?>
                                <option value="<?php echo $area['id']; ?>" 
                                        <?php echo $area['id'] == $documento['area_destino_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($area['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="contenido">Contenido del Documento *</label>
                    <textarea id="contenido" name="contenido" class="form-textarea" required 
                              placeholder="Escriba el contenido del memorando..."><?php echo htmlspecialchars($documento['contenido']); ?></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="urgencia">Urgencia *</label>
                        <select id="urgencia" name="urgencia" class="form-select" required>
                            <option value="ordinario" <?php echo $documento['urgencia'] === 'ordinario' ? 'selected' : ''; ?>>Ordinario</option>
                            <option value="urgente" <?php echo $documento['urgencia'] === 'urgente' ? 'selected' : ''; ?>>Urgente</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="estatus_atencion">Estado de Atención *</label>
                        <select id="estatus_atencion" name="estatus_atencion" class="form-select" required>
                            <option value="pendiente" <?php echo $documento['estatus_atencion'] === 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                            <option value="proceso" <?php echo $documento['estatus_atencion'] === 'proceso' ? 'selected' : ''; ?>>En Proceso</option>
                            <option value="atendido" <?php echo $documento['estatus_atencion'] === 'atendido' ? 'selected' : ''; ?>>Atendido</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="fecha_requerida_respuesta">Fecha Requerida de Respuesta</label>
                        <input type="date" id="fecha_requerida_respuesta" name="fecha_requerida_respuesta" class="form-input" 
                               value="<?php echo $documento['fecha_requerida_respuesta']; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="fecha_limite">Fecha Límite</label>
                        <input type="date" id="fecha_limite" name="fecha_limite" class="form-input" 
                               value="<?php echo $documento['fecha_limite']; ?>">
                    </div>
                </div>
                
                <button type="submit" class="btn-submit">💾 Actualizar Documento</button>
            </form>
        </div>
    </div>
</body>
</html>
