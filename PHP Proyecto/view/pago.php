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
require_once '../models/pago.php';

$db = (new Database())->getConnection();
$pago = new pago($db);
$stmt = $pago->read(); 
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
            <a class="nav-link text-danger mt-5" href="../controllers/AuthController.php?action=logout">
                <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
            </a>
        </nav>
    </div>

    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Bienvenido, <span class="text-primary"><?= htmlspecialchars($_SESSION['user_name']) ?></span></h4>
            <button class="btn btn-primary shadow-sm" onclick="openpagModal()">
                <i class="fas fa-plus me-2"></i> Nuevo Pago
            </button>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <table class="table table-hover m-0" id="paymentsTable">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Cliente</th>
                            <th>Metodo de Pago</th>
                            <th>Cantidad</th> 
                            <th>Fecha</th> 
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td class="ps-4 fw-medium"><?= $row['client'] ?></td>
                            <td><?= $row['payment_method'] ?></td>
                            <!-- : Escribimos el símbolo de peso manualmente antes de la función.
                            number_format(...): Es la función que da formato al número.
                            0: Indica que no queremos decimales (si quieres centavos, cambia el 0 por un 2).
                            ',': Es el separador de los decimales (en este caso una coma).
                            '.': Es el punto que separará las millas para que se vean como 500.000 . -->
                            <td><?= number_format($row['amount'], 0, ',', '.' ) ?></td> <td><?=$row['date'] ?></td> <td>
                                <span class="badge rounded-pill <?= $row['status'] ? 'bg-success' : 'bg-secondary' ?>">
                                    <?= $row['status'] ? 'Activo' : 'Inactivo' ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <button onclick="openpagModal(<?= $row['id'] ?>)" class="btn btn-sm btn-info text-white shadow-sm">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deletepag(<?= $row['id'] ?>)" class="btn btn-sm btn-danger shadow-sm">
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

    <div class="modal fade" id="pagModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="pagForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Nuevo Pago</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="pagId">
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold">CLIENTE</label>
                            <input type="text" name="client" id="pagClient" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">METODO DE PAGO</label>
                            <input type="text" name="payment_method" id="pagPayment_method" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">CANTIDAD</label>
                            <input type="number" name="amount" id="pagAmount" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">FECHA</label>
                            <input type="date" name="date" id="pagDate" class="form-control" required>
                        </div>
                        <div class="mb-3 form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="status" id="pagStatus" value="1" checked>
                            <label class="form-check-label">Pago Activo</label>
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
        let pagModal;
        let pagForm;

        document.addEventListener('DOMContentLoaded', function() {
            const modalElement = document.getElementById('pagModal');
            pagModal = new bootstrap.Modal(modalElement);
            pagForm = document.getElementById('pagForm');
            
            if(pagForm) {
                pagForm.onsubmit = async (e) => {
                    e.preventDefault();
                    const formData = new FormData(pagForm);
                    if(!formData.has('status')) formData.append('status', '0');

                    try {
                        const resp = await fetch('../controllers/pagoController.php?action=save', {
                            method: 'POST',
                            body: formData
                        });
                        const res = await resp.json();
                        if(res.status === 'success') {
                            Swal.fire('¡Éxito!', 'Pago guardado', 'success').then(() => location.reload());
                        } else {
                            Swal.fire('Error', res.message, 'error');
                        }
                    } catch (err) {
                        Swal.fire('Error', 'No se pudo procesar la solicitud', 'error');
                    }
                };
            }
        });

        function openpagModal(id = null) {
            if (pagForm) {
                pagForm.reset();
                document.getElementById('pagId').value = id || '';
                document.getElementById('modalTitle').innerText = id ? 'Editar Pago' : 'Nuevo Pago';

                if (id) {
                    const row = document.querySelector(`button[onclick="openpagModal(${id})"]`).closest('tr');
                    document.getElementById('pagClient').value = row.cells[0].innerText;
                    document.getElementById('pagPayment_method').value = row.cells[1].innerText;
                    document.getElementById('pagAmount').value = row.cells[2].innerText; // NUEVO
                    document.getElementById('pagDate').value = row.cells[3].innerText;  // NUEVO
                }
                pagModal.show();
            }
        }

        function deletepag(id) {
            Swal.fire({
                title: '¿Eliminar pago?',
                text: "Esta acción borrará al pago de la base de datos.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    const response = await fetch(`../controllers/pagoController.php?action=delete&id=${id}`);
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