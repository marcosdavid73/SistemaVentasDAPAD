<!-- Topbar -->
<nav class="navbar navbar-expand topbar mb-4 static-top" style="display: flex; justify-content: space-between; width: 100%;">
    <div style="flex: 1;"></div>
    <ul class="navbar-nav">
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small" style="margin-right: 0.5rem;">
                    <strong><?php echo htmlspecialchars($_SESSION['nombre'] ?? 'Usuario'); ?></strong>
                    <br>
                    <small style="color: #858796;"><?php echo ucfirst($_SESSION['rol'] ?? ''); ?></small>
                </span>
                <i class="fas fa-user-circle fa-2x" style="color: #858796;"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown" style="min-width: 200px;">
                <li><h6 class="dropdown-header" style="background: var(--primary-color); color: white; margin: -0.5rem -1rem 0.5rem; padding: 0.75rem 1rem;">
                    <i class="fas fa-user-circle"></i> Mi Cuenta
                </h6></li>
                <li><a class="dropdown-item" href="#" style="pointer-events: none; opacity: 0.6;">
                    <i class="fas fa-id-card fa-sm fa-fw mr-2" style="color: #858796; margin-right: 0.5rem;"></i>
                    Mi Perfil (Próximamente)
                </a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="index.php" target="_blank">
                    <i class="fas fa-store fa-sm fa-fw mr-2" style="color: #858796; margin-right: 0.5rem;"></i>
                    Ver Catálogo Público
                </a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="cerrar_sesion.php">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2" style="color: #e74a3b; margin-right: 0.5rem;"></i>
                    Cerrar Sesión
                </a></li>
            </ul>
        </li>
    </ul>
</nav>
