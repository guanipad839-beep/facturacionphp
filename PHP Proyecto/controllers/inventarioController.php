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
require_once '../models/inventario.php'; 

$action = $_GET['action'] ?? ''; 

try {
    $database = new Database(); 
    $db = $database->getConnection();

    if ($action === 'save') {
        $id = $_POST['id'] ?? ''; 
        $product = $_POST['product'] ?? '';
        $category = $_POST['category'] ?? '';
        $stock = $_POST['stock'] ?? 0; // NUEVO
        $price = $_POST['price'] ?? 0; // NUEVO
        $status = $_POST['status'] ?? 1; 

        if (empty($id)) {
            // INSERTAR NUEVO
            $query = "INSERT INTO inventory (product, category, stock, price, status) VALUES (?, ?, ?, ?, ?)";
            $stmt = $db->prepare($query);
            $success = $stmt->execute([$product, $category, $stock, $price, $status]); // NUEVO
        } else {
            // ACTUALIZAR EXISTENTE
            $query = "UPDATE inventory SET product = ?, category = ?, stock = ?, price = ?, status = ? WHERE id = ?"; // NUEVO
            $stmt = $db->prepare($query);
            $success = $stmt->execute([$product, $category, $stock, $price, $status, $id]); // NUEVO
        }
        echo json_encode(['status' => $success ? 'success' : 'error']);
    }

    if ($action === 'delete') {
        $id = $_GET['id'] ?? ''; 
        if (!empty($id)) {
            $query = "DELETE FROM inventory WHERE id = ?"; 
            $stmt = $db->prepare($query);
            $success = $stmt->execute([$id]);
            echo json_encode(['status' => $success ? 'success' : 'error']);
        }
    }

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}