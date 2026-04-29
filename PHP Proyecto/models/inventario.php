<?php
// Escudo de seguridad: Si la clase ya fue cargada, no la vuelvas a declarar.
if (!class_exists('inventario')) {

    class inventario {
        private $conn; 
        public $id, $product, $category, $stock, $price, $status; 

        public function __construct($db) { 
            $this->conn = $db; 
        }

        public function read() {
            return $this->conn->query("SELECT * FROM inventory ORDER BY id DESC");
        }

        public function readOne() {
            $stmt = $this->conn->prepare("SELECT * FROM inventory WHERE id = ?");
            $stmt->execute([$this->id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function create() {
            $sql = "INSERT INTO inventory SET product=:prod, category=:cat, stock=:stk, price=:pri, status=1";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':prod' => $this->product,
                ':cat' => $this->category,
                ':stk' => $this->stock, // NUEVO
                ':pri' => $this->price // NUEVO
            ]);
        }

        public function update() {
            $sql = "UPDATE inventory SET product=:prod, category=:cat, stock=:stk, price=:pri WHERE id=:id"; // NUEVO
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':prod' => $this->product,
                ':cat' => $this->category,
                ':stk' => $this->stock, // NUEVO
                ':pri' => $this->price, // NUEVO
                ':id' => $this->id
            ]);
        }

        public function toggleStatus() {
            $stmt = $this->conn->prepare("UPDATE inventory SET status = NOT status WHERE id = ?");
            return $stmt->execute([$this->id]);
        }

        public function delete() {
            $stmt = $this->conn->prepare("DELETE FROM inventory WHERE id = ?");
            return $stmt->execute([$this->id]);
        }

        public function countinventario(){
            $stmt = $this->conn->query("SELECT COUNT(id) AS cantidad FROM inventory");
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['cantidad'];
        }
    }
}