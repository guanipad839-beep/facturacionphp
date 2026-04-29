<?php
// Escudo de seguridad: Si la clase ya fue cargada, no la vuelvas a declarar.
if (!class_exists('garantia')) {

    class garantia {
        private $conn; 
        public $id, $guarantees, $Order_details, $Start_Date, $End_Date, $status; 

        public function __construct($db) { 
            $this->conn = $db; 
        }

        public function read() {
            return $this->conn->query("SELECT * FROM guarantees ORDER BY id DESC");
        }

        public function readOne() {
            $stmt = $this->conn->prepare("SELECT * FROM guarantees WHERE id = ?");
            $stmt->execute([$this->id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function create() {
            $sql = "INSERT INTO guarantees SET guarantees=:gs, Order_details=:od, Start_Date=:sd, End_Date=:ed, status=1";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':gs' => $this->guarantees,
                ':od' => $this->Order_details,
                ':sd' => $this->Start_Date, // NUEVO
                ':ed' => $this->End_Date // NUEVO
            ]);
        }

        public function update() {
            $sql = "UPDATE guarantees SET guarantees=:gs, Order_details=:od, Start_Date=:sd, End_Date=:ed WHERE id=:id"; // NUEVO
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':gs' => $this->guarantees,
                ':od' => $this->Order_details,
                ':sd' => $this->Start_Date, // NUEVO
                ':ed' => $this->End_Date, // NUEVO
                ':id' => $this->id
            ]);
        }

        public function toggleStatus() {
            $stmt = $this->conn->prepare("UPDATE guarantees SET status = NOT status WHERE id = ?");
            return $stmt->execute([$this->id]);
        }

        public function delete() {
            $stmt = $this->conn->prepare("DELETE FROM guarantees WHERE id = ?");
            return $stmt->execute([$this->id]);
        }

          public function countgarantia(){
            $stmt = $this->conn->query("SELECT COUNT(id) AS total FROM guarantees");
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['total'];
        }
    }
}