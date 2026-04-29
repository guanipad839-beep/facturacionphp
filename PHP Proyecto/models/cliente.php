<?php
// Escudo de seguridad: Si la clase ya fue cargada, no la vuelvas a declarar.
if (!class_exists('cliente')) {

    class cliente {
        private $conn; 
        public $id, $full_name, $phone,	$email,	$registration_date, $status; 

        public function __construct($db) { 
            $this->conn = $db; 
        }

        public function read() {
            return $this->conn->query("SELECT * FROM clients ORDER BY id DESC");
        }

        public function readOne() {
            $stmt = $this->conn->prepare("SELECT * FROM clients WHERE id = ?");
            $stmt->execute([$this->id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function create() {
            $sql = "INSERT INTO clients SET full_name=:fn, phone=:ph, email=:em, registration_date=:rd, status=1";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':fn' => $this->full_name,
                ':ph' => $this->phone,
                ':em' => $this->email, // NUEVO
                ':rd' => $this->registration_date // NUEVO
            ]);
        }

        public function update() {
            $sql = "UPDATE clients SET full_name=:fn, phone=:ph, email=:em, registration_date=:rd WHERE id=:id"; // NUEVO
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':fn' => $this->full_name,
                ':ph' => $this->phone,
                ':em' => $this->email, // NUEVO
                ':hd' => $this->registration_date, // NUEVO
                ':id' => $this->id
            ]);
        }

        public function toggleStatus() {
            $stmt = $this->conn->prepare("UPDATE clients SET status = NOT status WHERE id = ?");
            return $stmt->execute([$this->id]);
        }

        public function delete() {
            $stmt = $this->conn->prepare("DELETE FROM clients WHERE id = ?");
            return $stmt->execute([$this->id]);
        }

        public function countcliente(){
            $stmt = $this->conn->query("SELECT COUNT(id) AS cantidad FROM clients");
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['cantidad'];
        }
    }
}