<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once './config/Database.php';
require_once './models/Employee.php';
require_once './models/cliente.php';
require_once './models/inventario.php';
require_once './models/pedido.php';
require_once './models/pago.php';
require_once './models/garantia.php';

$db = (new Database())->getConnection();

$employee = new Employee($db);
$stmt = $employee->read();
$cantEmployee = $employee->countEmployees(); 

$cliente = new cliente($db);
$totalcliente = $cliente->countcliente();

$inventario = new inventario($db);
$cantinventario = $inventario->countinventario();

$pedido = new pedido($db);
$totalpedido = $pedido->countpedido();

$pago = new pago($db);
$cantpago = $pago->countpago();

$garantia = new garantia($db);
$totalgarantia = $garantia->countgarantia();


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Dashboard - Gestión</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="d-flex bg-light">

    <!-- SIDEBAR -->
    <div class="bg-dark text-white vh-100 p-3" style="min-width: 250px;">
        <div class="text-center border-bottom pb-3 mb-3">
            <h5 class="fw-bold text-primary m-0">PROYECTO</h5>
        </div>

        <nav class="nav flex-column">
            <a class="nav-link.dashboard text-white bg-primary mb-1" href="./index.php">
                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
            </a>
            <a class="nav-link.empleado text-white mb-1" href="./view/employee.php">
                <i class="fas fa-users me-2"></i> Empleados
            </a>
            <a class="nav-link.cliente text-white mb-1" href="./view/cliente.php">
                <i class="fas fa-address-book me-2"></i> Clientes
            </a>
            <a class="nav-link.inventario text-white mb-1" href="./view/inventario.php">
                <i class="fas fa-calculator me-2"></i> Inventarios
            </a>

             <a class="nav-link.pedido text-white mb-1" href="./view/pedido.php">
                <i class="fas fa-square-poll-vertical me-2"></i> Pedidos
            </a>

             <a class="nav-link.pago text-white mb-1" href="./view/pago.php">
                <i class="fas fa-wallet me-2"></i> Pagos
            </a>

             <a class="nav-link.garantia text-white mb-1" href="./view/garantia.php">
                <i class="fas fa-calendar me-2"></i> Garantias
            </a>

            <a class="nav-link text-danger mt-4" href="controllers/AuthController.php?action=logout">
                <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
            </a>
        </nav>
    </div>

    <!-- CONTENIDO -->
    <div class="container-fluid p-4">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">
                Bienvenido,
                <span class="text-primary"><?= htmlspecialchars($_SESSION['user_name']) ?></span>
            </h4>
        </div>

        <!-- CARDS -->
        <div class="row g-3">

            <!-- CARD CURSOS -->
            <div class="col-md-4">
                <div class="card text-bg-primary shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Empleados</h6>
                            <h2 class="mb-0"><?=  $cantEmployee ?></h2>
                        </div>
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>

            <!-- Puedes duplicar esta card para más métricas -->
            <div class="col-md-4">
                <div class="card text-bg-primary shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Clientes</h6>
                            <h2 class="mb-0"><?= $totalcliente?></h2>
                        </div>
                        <i class="fa-solid fa-address-book fa-2x"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-bg-primary shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Inventarios</h6>
                            <h2 class="mb-0"><?= $cantinventario?></h2>
                        </div>
                        <i class="fas fa-calculator fa-2x"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-bg-primary shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Pedidos</h6>
                            <h2 class="mb-0"><?= $totalpedido?></h2>
                        </div>
                       <i class="fa-solid fa-square-poll-vertical fa-2x"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-bg-primary shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Pagos</h6>
                            <h2 class="mb-0"><?= $cantpago?></h2>
                        </div>
                        <i class="fa-solid fa-wallet fa-2x"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-bg-primary shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Garantias</h6>
                            <h2 class="mb-0"><?= $totalgarantia?></h2>
                        </div>
                        <i class="fas fa-calendar fa-2x"></i>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/index.js"></script>

</body>

</html>