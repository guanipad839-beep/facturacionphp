<?php
session_start(); // Inicia o reanuda la sesión del usuario en el servidor

// Requerimos los archivos necesarios para conectarnos y usar el modelo
require_once '../config/Database.php';
require_once '../models/User.php';

/**
 * EXPLICACIÓN DEL OPERADOR ??:
 * Se llama "Null Coalesce". 
 * $_GET['action'] ?? '' significa: 
 * "Si existe 'action' en la URL, úsalo. Si no existe (es null), usa un texto vacío ''".
 * Esto evita el error de "Undefined index".
 */
$action = $_GET['action'] ?? ''; 

// Lógica de Cerrar Sesión
if ($action === 'logout') {
    session_destroy(); // Borra toda la información de la sesión en el servidor
    header("Location: ../login.php"); // Redirige físicamente al navegador al login
    exit(); // Detiene la ejecución del script
}

// Indicamos que la respuesta de aquí en adelante será un objeto JSON para JavaScript
header('Content-Type: application/json');

try {
    $database = new Database(); // Creamos instancia de la base de datos
    $db = $database->getConnection(); // Obtenemos la conexión PDO
    $user = new User($db); // Creamos el objeto Usuario pasándole la conexión

    if ($action === 'login') {
        // Aplicamos ?? también aquí para evitar errores si los campos llegan vacíos
        $u = $_POST['username'] ?? ''; 
        $p = $_POST['password'] ?? ''; 

        // Llamamos a la función de login del modelo
        $userData = $user->login($u, $p);

        if ($userData) {
            // Si el login es correcto, guardamos datos en la sesión
            $_SESSION['user_id'] = $userData['id'];
            $_SESSION['user_name'] = $userData['username']; 
            // Enviamos éxito al SweetAlert del frontend
            echo json_encode(['status' => 'success']);
        } else {
            // Enviamos error si las credenciales no coinciden
            echo json_encode(['status' => 'error', 'message' => 'Usuario o clave incorrectos']);
        }
        exit();
    }
} catch (Exception $e) {
    // Si algo explota (como el error 1054 que vimos), lo capturamos aquí
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}