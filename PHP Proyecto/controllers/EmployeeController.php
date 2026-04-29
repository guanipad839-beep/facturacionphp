<?php
/**
 * EmployeeController.php: Gestiona CRUD completo (Crear, Leer, Actualizar, Borrar).
 */
session_start(); 
header('Content-Type: application/json'); 

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Acceso denegado']);
    exit();
}

require_once '../config/Database.php'; 
require_once '../models/Employee.php'; 

$action = $_GET['action'] ?? ''; 

try {
    $database = new Database(); 
    $db = $database->getConnection();

    if ($action === 'save') {
        $id = $_POST['id'] ?? ''; 
        $full_name = $_POST['full_name'] ?? '';
        $position = $_POST['position'] ?? '';
        $email = $_POST['email'] ?? ''; // NUEVO
        $hire_date = $_POST['hire_date'] ?? date('Y-m-d'); // NUEVO
        $status = $_POST['status'] ?? 0; 

        if (empty($id)) {
            // INSERTAR NUEVO
            $query = "INSERT INTO employees (full_name, position, email, hire_date, status) VALUES (?, ?, ?, ?, ?)";
            $stmt = $db->prepare($query);
            $success = $stmt->execute([$full_name, $position, $email, $hire_date, $status]); // NUEVO
        } else {
            // ACTUALIZAR EXISTENTE
            $query = "UPDATE employees SET full_name = ?, position = ?, email = ?, hire_date = ?, status = ? WHERE id = ?"; // NUEVO
            $stmt = $db->prepare($query);
            $success = $stmt->execute([$full_name, $position, $email, $hire_date, $status, $id]); // NUEVO
        }
        echo json_encode(['status' => $success ? 'success' : 'error']);
    }

    if ($action === 'delete') {
        $id = $_GET['id'] ?? ''; 
        if (!empty($id)) {
            $query = "DELETE FROM employees WHERE id = ?"; 
            $stmt = $db->prepare($query);
            $success = $stmt->execute([$id]);
            echo json_encode(['status' => $success ? 'success' : 'error']);
        }
    }

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}