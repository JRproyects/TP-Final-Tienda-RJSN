<?php
// Iniciar sesión solo si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está logueado
if(!isset($_SESSION['idusuario'])) {
    header("Location: ../public/index.php");
    exit;
}

// Mostrar errores
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control - Tienda Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .welcome-section {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 40px 0;
            margin-bottom: 30px;
            border-radius: 10px;
        }
        .role-card {
            transition: transform 0.2s;
        }
        .role-card:hover {
            transform: translateY(-5px);
        }
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">
                <i class="fas fa-tachometer-alt"></i> Panel de Control
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['usnombre']); ?>
                </span>
                <a class="nav-link btn btn-outline-light btn-sm me-2" href="../public/index.php">
                    <i class="fas fa-store"></i> Ir a Tienda
                </a>
                <a class="nav-link btn btn-outline-light btn-sm" href="../controllers/AuthController.php?action=logout">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Sección de Bienvenida -->
        <div class="welcome-section text-center">
            <h1><i class="fas fa-user-shield"></i> Vista Privada</h1>
            <p class="lead">Acceso exclusivo para usuarios registrados</p>
        </div>

        <!-- Información del Usuario -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="fas fa-id-card"></i> Información de tu Cuenta</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <p><strong><i class="fas fa-user"></i> Usuario:</strong><br>
                                <?php echo htmlspecialchars($_SESSION['usnombre']); ?></p>
                            </div>
                            <div class="col-md-3">
                                <p><strong><i class="fas fa-envelope"></i> Email:</strong><br>
                                <?php echo htmlspecialchars($_SESSION['usmail']); ?></p>
                            </div>
                            <div class="col-md-3">
                                <p><strong><i class="fas fa-user-tag"></i> Roles:</strong><br>
                                    <?php 
                                    if(isset($_SESSION['roles']) && is_array($_SESSION['roles'])) {
                                        echo '<span class="badge bg-primary">' . implode('</span> <span class="badge bg-secondary">', $_SESSION['roles']) . '</span>';
                                    }
                                    ?>
                                </p>
                            </div>
                            <div class="col-md-3">
                                <p><strong><i class="fas fa-calendar"></i> Sesión:</strong><br>
                                <?php echo date('d/m/Y H:i:s'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas Rápidas -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card stats-card text-center">
                    <div class="card-body">
                        <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                        <h4>15</h4>
                        <p class="mb-0">Compras Realizadas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card text-center">
                    <div class="card-body">
                        <i class="fas fa-box fa-2x mb-2"></i>
                        <h4>8</h4>
                        <p class="mb-0">Productos Activos</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card text-center">
                    <div class="card-body">
                        <i class="fas fa-users fa-2x mb-2"></i>
                        <h4>124</h4>
                        <p class="mb-0">Clientes Registrados</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card text-center">
                    <div class="card-body">
                        <i class="fas fa-chart-line fa-2x mb-2"></i>
                        <h4>$5,240</h4>
                        <p class="mb-0">Ingresos Mensuales</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Funcionalidades por Rol -->
        <div class="row">
            <?php if(isset($_SESSION['roles']) && in_array('cliente', $_SESSION['roles'])): ?>
            <div class="col-md-4 mb-4">
                <div class="card role-card h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0"><i class="fas fa-shopping-cart"></i> Cliente</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Funciones disponibles para clientes:</p>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><i class="fas fa-check text-success"></i> Ver historial de compras</li>
                            <li class="list-group-item"><i class="fas fa-check text-success"></i> Gestionar perfil</li>
                            <li class="list-group-item"><i class="fas fa-check text-success"></i> Ver productos</li>
                            <li class="list-group-item"><i class="fas fa-check text-success"></i> Realizar compras</li>
                        </ul>
                    </div>
                    <div class="card-footer">
                        <a href="../public/index.php" class="btn btn-primary w-100">
                            <i class="fas fa-store"></i> Ir de Compras
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if(isset($_SESSION['roles']) && in_array('deposito', $_SESSION['roles'])): ?>
            <div class="col-md-4 mb-4">
                <div class="card role-card h-100">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0"><i class="fas fa-warehouse"></i> Depósito</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Funciones disponibles para depósito:</p>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><i class="fas fa-check text-success"></i> Gestionar inventario</li>
                            <li class="list-group-item"><i class="fas fa-check text-success"></i> Controlar stock</li>
                            <li class="list-group-item"><i class="fas fa-check text-success"></i> Gestionar entregas</li>
                            <li class="list-group-item"><i class="fas fa-check text-success"></i> Ver reportes de stock</li>
                        </ul>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-success w-100">
                            <i class="fas fa-boxes"></i> Gestionar Stock
                        </button>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if(isset($_SESSION['roles']) && in_array('administrador', $_SESSION['roles'])): ?>
            <div class="col-md-4 mb-4">
                <div class="card role-card h-100">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="card-title mb-0"><i class="fas fa-crown"></i> Administrador</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Funciones disponibles para administradores:</p>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><i class="fas fa-check text-success"></i> Gestionar usuarios</li>
                            <li class="list-group-item"><i class="fas fa-check text-success"></i> Ver reportes completos</li>
                            <li class="list-group-item"><i class="fas fa-check text-success"></i> Configurar sistema</li>
                            <li class="list-group-item"><i class="fas fa-check text-success"></i> Todas las funciones</li>
                        </ul>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-warning w-100">
                            <i class="fas fa-cog"></i> Configuración
                        </button>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Acciones Rápidas -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="fas fa-bolt"></i> Acciones Rápidas</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2 text-center">
                                <a href="../public/index.php" class="btn btn-outline-primary w-100 mb-2">
                                    <i class="fas fa-home"></i><br>Inicio
                                </a>
                            </div>
                            <div class="col-md-2 text-center">
                                <button class="btn btn-outline-info w-100 mb-2">
                                    <i class="fas fa-user-edit"></i><br>Perfil
                                </button>
                            </div>
                            <div class="col-md-2 text-center">
                                <button class="btn btn-outline-success w-100 mb-2">
                                    <i class="fas fa-history"></i><br>Historial
                                </button>
                            </div>
                            <div class="col-md-2 text-center">
                                <button class="btn btn-outline-warning w-100 mb-2">
                                    <i class="fas fa-cog"></i><br>Ajustes
                                </button>
                            </div>
                            <div class="col-md-2 text-center">
                                <button class="btn btn-outline-secondary w-100 mb-2">
                                    <i class="fas fa-question-circle"></i><br>Ayuda
                                </button>
                            </div>
                            <div class="col-md-2 text-center">
                                <a href="../app/controllers/AuthController.php?action=logout" class="btn btn-outline-danger w-100 mb-2">
                                    <i class="fas fa-sign-out-alt"></i><br>Salir
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <div class="container">
            <p class="mb-0">
                <i class="fas fa-shield-alt"></i> Área Privada - Tienda Online &copy; <?php echo date('Y'); ?>
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>