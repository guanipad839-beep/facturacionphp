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
require_once '../models/pago.php'; 

$action = $_GET['action'] ?? ''; 

try {
    $database = new Database(); 
    $db = $database->getConnection();

    if ($action === 'save') {
        $id = $_POST['id'] ?? ''; 
        $client = $_POST['client'] ?? '';
        $payment_method = $_POST['payment_method'] ?? '';
        $amount = $_POST['amount'] ?? '' ; // NUEVO
        $date = $_POST['date'] ?? date('Y-m-d'); // NUEVO
        $status = $_POST['status'] ?? 1; 

        if (empty($id)) {
            // INSERTAR NUEVO
            $query = "INSERT INTO payments (client, payment_method, amount, date, status) VALUES (?, ?, ?, ?, ?)";
            $stmt = $db->prepare($query);
            $success = $stmt->execute([$client, $payment_method, $amount, $date, $status]); // NUEVO
        } else {
            // ACTUALIZAR EXISTENTE
            $query = "UPDATE payments SET client = ?, payment_method = ?, amount = ?, date = ?, status = ? WHERE id = ?"; // NUEVO
            $stmt = $db->prepare($query);
            $success = $stmt->execute([$client, $payment_method, $amount, $date, $status, $id]); // NUEVO
        }
        echo json_encode(['status' => $success ? 'success' : 'error']);
    }

    if ($action === 'delete') {
        $id = $_GET['id'] ?? ''; 
        if (!empty($id)) {
            $query = "DELETE FROM payments WHERE id = ?"; 
            $stmt = $db->prepare($query);
            $success = $stmt->execute([$id]);
            echo json_encode(['status' => $success ? 'success' : 'error']);
        }
    }

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}