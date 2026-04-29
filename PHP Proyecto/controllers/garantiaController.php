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
require_once '../models/garantia.php'; 

$action = $_GET['action'] ?? ''; 

try {
    $database = new Database(); 
    $db = $database->getConnection();

    if ($action === 'save') {
        $id = $_POST['id'] ?? ''; 
        $guarantees = $_POST['guarantees'] ?? '';
        $Order_details = $_POST['Order_details'] ?? '';
        $Start_Date = $_POST['Start_Date'] ?? date('Y-m-d'); // NUEVO
        $End_Date = $_POST['End_Date'] ?? date('Y-m-d'); // NUEVO
        $status = $_POST['status'] ?? 1; 

        if (empty($id)) {
            // INSERTAR NUEVO
            $query = "INSERT INTO guarantees (guarantees, Order_details, Start_Date, End_Date, status) VALUES (?, ?, ?, ?, ?)";
            $stmt = $db->prepare($query);
            $success = $stmt->execute([$guarantees, $Order_details, $Start_Date, $End_Date, $status]); // NUEVO
        } else {
            // ACTUALIZAR EXISTENTE
            $query = "UPDATE guarantees SET guarantees = ?, Order_details = ?, Start_Date = ?, End_Date = ?, status = ? WHERE id = ?"; // NUEVO
            $stmt = $db->prepare($query);
            $success = $stmt->execute([$guarantees, $Order_details, $Start_Date, $End_Date, $status, $id]); // NUEVO
        }
        echo json_encode(['status' => $success ? 'success' : 'error']);
    }

    if ($action === 'delete') {
        $id = $_GET['id'] ?? ''; 
        if (!empty($id)) {
            $query = "DELETE FROM guarantees WHERE id = ?"; 
            $stmt = $db->prepare($query);
            $success = $stmt->execute([$id]);
            echo json_encode(['status' => $success ? 'success' : 'error']);
        }
    }

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}