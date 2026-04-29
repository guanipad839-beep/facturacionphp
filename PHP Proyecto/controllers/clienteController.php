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
require_once '../models/cliente.php'; 

$action = $_GET['action'] ?? ''; 

try {
    $database = new Database(); 
    $db = $database->getConnection();

    if ($action === 'save') {
        $id = $_POST['id'] ?? ''; 
        $full_name = $_POST['full_name'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $email = $_POST['email'] ?? ''; // NUEVO
        $reg_date = $_POST['registration_date'] ?? date('Y-m-d'); // NUEVO
        $status = $_POST['status'] ?? 1; 

        if (empty($id)) {
            // INSERTAR NUEVO
            $query = "INSERT INTO clients (full_name, phone, email, registration_date, status) VALUES (?, ?, ?, ?, ?)";
            $stmt = $db->prepare($query);
            $success = $stmt->execute([$full_name, $phone, $email, $reg_date, $status]); // NUEVO
        } else {
            // ACTUALIZAR EXISTENTE
            $query = "UPDATE clients SET full_name = ?, phone = ?, email = ?, registration_date = ?, status = ? WHERE id = ?"; // NUEVO
            $stmt = $db->prepare($query);
            $success = $stmt->execute([$full_name, $phone, $email, $reg_date, $status, $id]); // NUEVO
        }
        echo json_encode(['status' => $success ? 'success' : 'error']);
    }

    if ($action === 'delete') {
        $id = $_GET['id'] ?? ''; 
        if (!empty($id)) {
            $query = "DELETE FROM clients WHERE id = ?"; 
            $stmt = $db->prepare($query);
            $success = $stmt->execute([$id]);
            echo json_encode(['status' => $success ? 'success' : 'error']);
        }
    }

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}