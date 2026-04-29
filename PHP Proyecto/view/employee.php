<?php
/**
 * index.php: Panel de control principal con lógica de guardado activa.
 */
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once '../config/Database.php';
require_once '../models/Employee.php';

$db = (new Database())->getConnection();
$employee = new Employee($db);
$stmt = $employee->read(); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Gestión de Empleados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { min-width: 250px; height: 100vh; background: #212529; color: white; position: sticky; top: 0; }
        .nav-link { color: #adb5bd; padding: 15px; transition: 0.3s; }
        .nav-link:hover { color: white; background: #379bff; }
        /* .nav-link.active { color: white; background: #ffd30d; } */
        .nav-link.dashboard{color:white; background: #2c07ff; }
        .nav-link.empleado{color: white; background: #2c07ff;}
        .nav-link.cliente{color: white; background: #2c07ff;}
        .nav-link.inventario{color: white; background: #2c07ff;}
        .nav-link.pedido{color: white; background: #2c07ff;}
        .nav-link.pago{color: white; background: #2c07ff;}
        .nav-link.garantia{color: white; background: #2c07ff;}
    </style>
</head>
<body class="d-flex">

    <div class="sidebar shadow">
        <div class="p-4 text-center border-bottom border-secondary">
            <h5 class="fw-bold m-0 text-primary">PROYECTO</h5>
        </div>
        <nav class="nav flex-column mt-3">
             <a class="nav-link dashboard" href="../index.php"><i class="fas fa-tachometer me-2"></i> Dashboard</a>
            <a class="nav-link empleado" href="../view/employee.php"><i class="fas fa-users me-2"></i> Empleados</a>
            <a class="nav-link cliente" href="../view/cliente.php"><i class="fas fa-address-book me-2"></i> Clientes</a>
            <a class="nav-link inventario" href="../view/inventario.php"><i class="fas fa-calculator me-2"></i> Inventarios</a>
            <a class="nav-link pedido" href="../view/pedido.php"><i class="fas fa-square-poll-vertical me-2"></i> Pedidos</a>
            <a class="nav-link pago" href="../view/pago.php"><i class="fas fa-wallet me-2"></i> Pagos</a>
            <a class="nav-link garantia" href="../view/garantia.php"><i class="fas fa-calendar me-2"></i> Garantias</a>
            <a class="nav-link text-danger mt-5" href="controllers/AuthController.php?action=logout">
                <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
            </a>
        </nav>
    </div>

    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Bienvenido, <span class="text-primary"><?= htmlspecialchars($_SESSION['user_name']) ?></span></h4>
            <button class="btn btn-primary shadow-sm" onclick="openModal()">
                <i class="fas fa-plus me-2"></i> Nuevo Empleado
            </button>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <table class="table table-hover m-0" id="employeeTable">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Nombre Completo</th>
                            <th>Puesto / Cargo</th>
                            <th>Email</th> 
                            <th>Fecha Ingreso</th> 
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td class="ps-4 fw-medium"><?= $row['full_name'] ?></td>
                            <td><?= $row['position'] ?></td>
                            <td><?= $row['email'] ?></td> <td><?= $row['hire_date'] ?></td> <td>
                                <span class="badge rounded-pill <?= $row['status'] ? 'bg-success' : 'bg-secondary' ?>">
                                    <?= $row['status'] ? 'Activo' : 'Inactivo' ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <button onclick="openModal(<?= $row['id'] ?>)" class="btn btn-sm btn-info text-white shadow-sm">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteEmp(<?= $row['id'] ?>)" class="btn btn-sm btn-danger shadow-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="empModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="empForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Nuevo Empleado</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="empId">
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold">NOMBRE COMPLETO</label>
                            <input type="text" name="full_name" id="empName" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">CORREO ELECTRÓNICO</label>
                            <input type="email" name="email" id="empEmail" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">PUESTO</label>
                            <input type="text" name="position" id="empPosition" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">FECHA DE CONTRATACIÓN</label>
                            <input type="date" name="hire_date" id="empHire" class="form-control" required>
                        </div>
                        <div class="mb-3 form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="status" id="empStatus" value="1" checked>
                            <label class="form-check-label">Empleado Activo</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/index.js"></script>
</body>
</html>