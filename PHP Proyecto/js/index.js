let myModal;
let empForm;

document.addEventListener('DOMContentLoaded', function() {
    const modalElement = document.getElementById('empModal');
    myModal = new bootstrap.Modal(modalElement);
    empForm = document.getElementById('empForm');
    
    if(empForm) {
        empForm.onsubmit = async (e) => {
            e.preventDefault();
            const formData = new FormData(empForm);
            if(!formData.has('status')) formData.append('status', '0');

            try {
                const resp = await fetch('../controllers/EmployeeController.php?action=save', {
                    method: 'POST',
                    body: formData
                });
                const res = await resp.json();
                if(res.status === 'success') {
                    Swal.fire('¡Éxito!', 'Empleado guardado', 'success').then(() => location.reload());
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            } catch (err) {
                Swal.fire('Error', 'No se pudo procesar la solicitud', 'error');
            }
        };
    }
});

function openModal(id = null) {
    if (empForm) {
        empForm.reset();
        document.getElementById('empId').value = id || '';
        document.getElementById('modalTitle').innerText = id ? 'Editar Empleado' : 'Nuevo Empleado';

        if (id) {
            const row = document.querySelector(`button[onclick="openModal(${id})"]`).closest('tr');
            document.getElementById('empName').value = row.cells[0].innerText;
            document.getElementById('empPosition').value = row.cells[1].innerText;
            document.getElementById('empEmail').value = row.cells[2].innerText;
            document.getElementById('empHire').value = row.cells[3].innerText;
        }
        myModal.show();
    }
}

function deleteEmp(id) {
    Swal.fire({
        title: '¿Eliminar empleado?',
        text: "Esta acción borrará al empleado de la base de datos.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar'
    }).then(async (result) => {
        if (result.isConfirmed) {
            const response = await fetch(`../controllers/EmployeeController.php?action=delete&id=${id}`);
            const res = await response.json();
            if (res.status === 'success') {
                Swal.fire('¡Borrado!', 'El registro ha sido eliminado.', 'success').then(() => location.reload());
            }
        }
    });
}
