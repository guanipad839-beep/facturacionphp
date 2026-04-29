document.getElementById('loginForm').onsubmit = async (e) => {
    e.preventDefault(); // Evita que el formulario se envíe de forma tradicional.

    const formData = new FormData(e.target);

    try {
        // Enviamos los datos al controlador mediante FETCH (AJAX).
        const response = await fetch('controllers/AuthController.php?action=login', {
            method: 'POST',
            body: formData
        });

        // Convertimos la respuesta a texto primero para poder ver errores de PHP si ocurren.
        const text = await response.text();
        
        try {
            const data = JSON.parse(text); // Intentamos convertir a objeto JSON.
            
            if (data.status === 'success') {
                window.location.href = 'index.php'; // Si todo está ok, vamos al dashboard.
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: data.message });
            }
        } catch (jsonErr) {
            console.error("Respuesta inesperada del servidor:", text);
            Swal.fire({ icon: 'warning', title: 'Error Crítico', text: 'El servidor no respondió un formato válido.' });
        }
    } catch (error) {
        Swal.fire({ icon: 'error', title: 'Fallo de Conexión', text: 'No se pudo contactar al servidor.' });
    }
};

const password = document.getElementById('password');
const togglePassword = document.getElementById('togglePassword');
const eyeOpen = document.getElementById('eyeOpen');
const eyeClosed = document.getElementById('eyeClosed');

togglePassword.addEventListener('click', () => {
  const show = password.type === 'password';
  password.type = show ? 'text' : 'password';
  eyeOpen.style.display = show ? 'none' : 'block';
  eyeClosed.style.display = show ? 'block' : 'none';
});