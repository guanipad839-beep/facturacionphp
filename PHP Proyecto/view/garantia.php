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
require_once '../models/garantia.php';

$db = (new Database())->getConnection();
$garantia = new garantia($db);
$stmt = $garantia->read(); 
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
            <button class="btn btn-primary shadow-sm" onclick="opengarModal()">
                <i class="fas fa-plus me-2"></i> Nueva Garantia 
            </button>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <table class="table table-hover m-0" id="guaranteesTable">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Garantias</th>
                            <th>Detalles Del Pedido</th>
                            <th>Fecha De Inicio</th> 
                            <th>Fecha De Finalizacion</th> 
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td class="ps-4 fw-medium"><?= $row['guarantees'] ?></td>
                            <td><?= $row['Order_details'] ?></td>
                            <td><?= $row['Start_Date'] ?></td> <td><?= $row['End_Date'] ?></td> <td>
                                <span class="badge rounded-pill <?= $row['status'] ? 'bg-success' : 'bg-secondary' ?>">
                                    <?= $row['status'] ? 'Activo' : 'Inactivo' ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <button onclick="opengarModal(<?= $row['id'] ?>)" class="btn btn-sm btn-info text-white shadow-sm">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deletegar(<?= $row['id'] ?>)" class="btn btn-sm btn-danger shadow-sm">
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

    <div class="modal fade" id="garModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="garForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Nueva Garantia</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="garId">
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold">GARANTIAS</label>
                            <input type="text" name="guarantees" id="garGuarantees" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">DETALLES DEL PEDIDO</label>
                            <input type="text" name="Order_details" id="garOrder_details" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">FECHA DE INICIO</label>
                            <input type="date" name="Start_Date" id="garStart_Date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">FECHA DE FINALIZACION</label>
                            <input type="date" name="End_Date" id="garEnd_Date" class="form-control" required>
                        </div>
                        <div class="mb-3 form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="status" id="garStatus" value="1" checked>
                            <label class="form-check-label">Garantia Activo</label>
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
        let garModal;
        let garForm;

        document.addEventListener('DOMContentLoaded', function() {
            const modalElement = document.getElementById('garModal');
            garModal = new bootstrap.Modal(modalElement);
            garForm = document.getElementById('garForm');
            
            if(garForm) {
                garForm.onsubmit = async (e) => {
                    e.preventDefault();
                    const formData = new FormData(garForm);
                    if(!formData.has('status')) formData.append('status', '0');

                    try {
                        const resp = await fetch('../controllers/garantiaController.php?action=save', {
                            method: 'POST',
                            body: formData
                        });
                        const res = await resp.json();
                        if(res.status === 'success') {
                            Swal.fire('¡Éxito!', 'Garantia guardado', 'success').then(() => location.reload());
                        } else {
                            Swal.fire('Error', res.message, 'error');
                        }
                    } catch (err) {
                        Swal.fire('Error', 'No se pudo procesar la solicitud', 'error');
                    }
                };
            }
        });

        function opengarModal(id = null) {
            if (garForm) {
                garForm.reset();
                document.getElementById('garId').value = id || '';
                document.getElementById('modalTitle').innerText = id ? 'Editar Garantia' : 'Nuevo Garantia';

                if (id) {
                    const row = document.querySelector(`button[onclick="opengarModal(${id})"]`).closest('tr');
                    document.getElementById('garGuarantees').value = row.cells[0].innerText;
                    document.getElementById('garOrder_details').value = row.cells[1].innerText;
                    document.getElementById('garStart_Date').value = row.cells[2].innerText; // NUEVO
                    document.getElementById('garEnd_Date').value = row.cells[3].innerText;  // NUEVO
                }
                garModal.show();
            }
        }

        function deletegar(id) {
            Swal.fire({
                title: '¿Eliminar garantia?',
                text: "Esta acción borrará al garantia de la base de datos.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    const response = await fetch(`../controllers/garantiaController.php?action=delete&id=${id}`);
                    const res = await response.json();
                    if (res.status === 'success') {
                        Swal.fire('¡Borrado!', 'El registro ha sido eliminado.', 'success').then(() => location.reload());
                    }
                }
            });
        }
    </script>
</body>
</html>