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
require_once '../models/cliente.php';

$db = (new Database())->getConnection();
$cliente = new cliente($db);
$stmt = $cliente->read(); 
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
        .nav-link { color: #adbdae; padding: 15px; transition: 0.3s; }
        .nav-link:hover { color: white; background: #000000; }
        .nav-link.active { color: white; background: #000000; } 
    </style>
</head>
<body class="d-flex">

    <div class="sidebar shadow">
        <div class="p-4 text-center border-bottom border-secondary">
            <h5 class="fw-bold m-0 text-primary">PROYECTO</h5>
        </div>
        <nav class="nav flex-column mt-3">
            <a class="nav-link active" href="../index.php"><i class="fas fa-tachometer me-2"></i> Dashboard</a>
            <a class="nav-link " href="../view/employee.php"><i class="fas fa-users me-2"></i> Empleados</a>
            <a class="nav-link " href="../view/cliente.php"><i class="fas fa-address-book me-2"></i> Clientes</a>
            <a class="nav-link " href="../view/inventario.php"><i class="fas fa-calculator me-2"></i> Inventarios</a>
            <a class="nav-link " href="../view/pedido.php"><i class="fas fa-square-poll-vertical me-2"></i> Pedidos</a>
            <a class="nav-link " href="../view/pago.php"><i class="fas fa-wallet me-2"></i> Pagos</a>
            <a class="nav-link " href="../view/garantia.php"><i class="fas fa-calendar me-2"></i> Garantias</a>
            <a class="nav-link text-danger mt-5" href="controllers/AuthController.php?action=logout">
                <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
            </a>
        </nav>
    </div>

    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Bienvenido, <span class="text-primary"><?= htmlspecialchars($_SESSION['user_name']) ?></span></h4>
            <button class="btn btn-primary shadow-sm" onclick="opencliModal()">
                <i class="fas fa-plus me-2"></i> Nuevo Cliente
            </button>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <table class="table table-hover m-0" id="clientsTable">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Nombre Completo</th>
                            <th>Telefono</th>
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
                            <td><?= $row['phone'] ?></td>
                            <td><?= $row['email'] ?></td> <td><?= $row['registration_date'] ?></td> <td>
                                <span class="badge rounded-pill <?= $row['status'] ? 'bg-success' : 'bg-secondary' ?>">
                                    <?= $row['status'] ? 'Activo' : 'Inactivo' ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <button onclick="opencliModal(<?= $row['id'] ?>)" class="btn btn-sm btn-info text-white shadow-sm">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deletecli(<?= $row['id'] ?>)" class="btn btn-sm btn-danger shadow-sm">
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

    <div class="modal fade" id="cliModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="cliForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Nuevo Cliente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="cliId">
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold">NOMBRE COMPLETO</label>
                            <input type="text" name="full_name" id="cliName" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">CORREO ELECTRÓNICO</label>
                            <input type="email" name="email" id="cliEmail" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">TELEFONO</label>
                            <input type="text" name="phone" id="cliPhone" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">FECHA DE CONTRATACIÓN</label>
                            <input type="date" name="registration_date" id="clireg" class="form-control" required>
                        </div>
                        <div class="mb-3 form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="status" id="cliStatus" value="1" checked>
                            <label class="form-check-label">Cliente Activo</label>
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

    <script>
        let cliModal;
        let cliForm;

        document.addEventListener('DOMContentLoaded', function() {
            const modalElement = document.getElementById('cliModal');
            cliModal = new bootstrap.Modal(modalElement);
            cliForm = document.getElementById('cliForm');
            
            if(cliForm) {
                cliForm.onsubmit = async (e) => {
                    e.preventDefault();
                    const formData = new FormData(cliForm);
                    if(!formData.has('status')) formData.append('status', '0');

                    try {
                        const resp = await fetch('../controllers/clienteController.php?action=save', {
                            method: 'POST',
                            body: formData
                        });
                        const res = await resp.json();
                        if(res.status === 'success') {
                            Swal.fire('¡Éxito!', 'Cliente guardado', 'success').then(() => location.reload());
                        } else {
                            Swal.fire('Error', res.message, 'error');
                        }
                    } catch (err) {
                        Swal.fire('Error', 'No se pudo procesar la solicitud', 'error');
                    }
                };
            }
        });

        function opencliModal(id = null) {
            if (cliForm) {
                cliForm.reset();
                document.getElementById('cliId').value = id || '';
                document.getElementById('modalTitle').innerText = id ? 'Editar Cliente' : 'Nuevo Cliente';

                if (id) {
                    const row = document.querySelector(`button[onclick="opencliModal(${id})"]`).closest('tr');
                    document.getElementById('cliName').value = row.cells[0].innerText;
                    document.getElementById('cliPhone').value = row.cells[1].innerText;
                    document.getElementById('cliEmail').value = row.cells[2].innerText; // NUEVO
                    document.getElementById('clireg').value = row.cells[3].innerText;  // NUEVO
                }
                cliModal.show();
            }
        }

        function deletecli(id) {
            Swal.fire({
                title: '¿Eliminar cliente?',
                text: "Esta acción borrará al cliente de la base de datos.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    const response = await fetch(`../controllers/clienteController.php?action=delete&id=${id}`);
                    const res = await response.json();
                    if (res.status === 'success') {
                        Swal.fire('¡Borrado!', 'El registro ha sido cliente.', 'success').then(() => location.reload());
                    }
                }
            });
        }
    </script>
</body>
</html>