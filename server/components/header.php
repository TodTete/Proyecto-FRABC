<?php
$base_url = '/project/public';
?>
<div class="header">
    <div class="header-left">
        <div class="logo">UTTECAM</div>
        <div class="page-title"><?php echo $page_title ?? 'Sistema de Documentos'; ?></div>
    </div>
    <div class="user-info">
        <div class="user-dropdown" id="userDropdown">
            <button class="user-trigger" onclick="toggleUserDropdown()">
                <div class="user-avatar">
                    <?php echo strtoupper(substr($usuario['nombre'], 0, 1)); ?>
                </div>
                <span><?php echo htmlspecialchars($usuario['nombre']); ?></span>
                <span class="dropdown-arrow">▼</span>
            </button>
            
            <div class="dropdown-menu">
                <div class="dropdown-header">
                    <div class="dropdown-avatar">
                        <?php echo strtoupper(substr($usuario['nombre'], 0, 1)); ?>
                    </div>
                    <div class="dropdown-name"><?php echo htmlspecialchars($usuario['nombre']); ?></div>
                    <div class="dropdown-email"><?php echo htmlspecialchars($usuario['correo']); ?></div>
                    <span class="dropdown-role"><?php echo ucfirst($usuario['rol']); ?></span>
                </div>
                
                <div class="dropdown-body">
                    <div class="dropdown-info">
                        <div class="info-item">
                            <span class="info-label">👤 Nombre:</span>
                            <span class="info-value"><?php echo htmlspecialchars($usuario['nombre']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">✉️ Correo:</span>
                            <span class="info-value"><?php echo htmlspecialchars($usuario['correo']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">🏷️ Rol:</span>
                            <span class="info-value"><?php echo ucfirst($usuario['rol']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">🆔 ID:</span>
                            <span class="info-value">#<?php echo str_pad($usuario['id'], 6, '0', STR_PAD_LEFT); ?></span>
                        </div>
                    </div>
                    
                    <form action="<?php echo $base_url; ?>/logout" method="POST">
                        <button type="submit" class="btn-logout">
                            🚪 Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleUserDropdown() {
    const dropdown = document.getElementById('userDropdown');
    dropdown.classList.toggle('active');
}

// Cerrar dropdown al hacer clic fuera
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('userDropdown');
    if (!dropdown.contains(event.target)) {
        dropdown.classList.remove('active');
    }
});
</script>
