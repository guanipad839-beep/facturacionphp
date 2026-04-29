<?php
// Escudo de seguridad: Si la clase ya fue cargada, no la vuelvas a declarar.
if (!class_exists('pago')) {

    class pago {
        private $conn; 
        public $id, $client, $payment_method, $amount, $date, $status; 

        public function __construct($db) { 
            $this->conn = $db; 
        }

        public function read() {
            return $this->conn->query("SELECT * FROM payments ORDER BY id DESC");
        }

        public function readOne() {
            $stmt = $this->conn->prepare("SELECT * FROM payments WHERE id = ?");
            $stmt->execute([$this->id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function create() {
            $sql = "INSERT INTO payments SET client=:cl, payment_method=:pm, amount=:am, date=:dt, status=1";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':cl' => $this->client,
                ':pm' => $this->payment_method,
                ':am' => $this->amount, // NUEVO
                ':dt' => $this->date // NUEVO
            ]);
        }

        public function update() {
            $sql = "UPDATE payments SET client=:cl, payment_method=:pm, amount=:am, date=:dt WHERE id=:id"; // NUEVO
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':cl' => $this->client,
                ':pm' => $this->payment_method,
                ':am' => $this->amount, // NUEVO
                ':dt' => $this->date, // NUEVO
                ':id' => $this->id
            ]);
        }

        public function toggleStatus() {
            $stmt = $this->conn->prepare("UPDATE payments SET status = NOT status WHERE id = ?");
            return $stmt->execute([$this->id]);
        }

        public function delete() {
            $stmt = $this->conn->prepare("DELETE FROM payments WHERE id = ?");
            return $stmt->execute([$this->id]);
        }

         public function countpago(){
            $stmt = $this->conn->query("SELECT COUNT(id) AS total FROM payments");
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['total'];
        }
    }
}