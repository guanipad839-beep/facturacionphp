<?php
// Escudo de seguridad: Si la clase ya fue cargada, no la vuelvas a declarar.
if (!class_exists('pedido')) {

    class pedido {
        private $conn; 
        public $id, $order_number, $client,	$date, $total, $status; 

        public function __construct($db) { 
            $this->conn = $db; 
        }

        public function read() {
            return $this->conn->query("SELECT * FROM orders ORDER BY id DESC");
        }

        public function readOne() {
            $stmt = $this->conn->prepare("SELECT * FROM orders WHERE id = ?");
            $stmt->execute([$this->id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function create() {
            $sql = "INSERT INTO orders SET order_number=:od, client=:cl, date=:dt, total=:tl, status=1";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':od' => $this->order_number,
                ':cl' => $this->client,
                ':dt' => $this->date, // NUEVO
                ':tl' => $this->total // NUEVO
            ]);
        }

        public function update() {
            $sql = "UPDATE orders SET order_number=:od, client=:cl, date=:dt, total=:tl WHERE id=:id"; // NUEVO
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':od' => $this->order_number,
                ':cl' => $this->client,
                ':dt' => $this->date, // NUEVO
                ':tl' => $this->total, // NUEVO
                ':id' => $this->id
            ]);
        }

        public function toggleStatus() {
            $stmt = $this->conn->prepare("UPDATE orders SET status = NOT status WHERE id = ?");
            return $stmt->execute([$this->id]);
        }

        public function delete() {
            $stmt = $this->conn->prepare("DELETE FROM orders WHERE id = ?");
            return $stmt->execute([$this->id]);
        }

         public function countpedido(){
            $stmt = $this->conn->query("SELECT COUNT(id) AS cantidad FROM orders");
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['cantidad'];
        }
    }
}