<?php
// Escudo de seguridad: Si la clase ya fue cargada, no la vuelvas a declarar.
if (!class_exists('Employee')) {

    class Employee {
        private $conn; 
        public $id, $full_name, $position, $email, $hire_date; // NUEVO: email e hire_date añadidos

        public function __construct($db) { 
            $this->conn = $db; 
        }

        public function read() {
            return $this->conn->query("SELECT * FROM employees ORDER BY id DESC");
        }

        public function readOne() {
            $stmt = $this->conn->prepare("SELECT * FROM employees WHERE id = ?");
            $stmt->execute([$this->id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function create() {
            $sql = "INSERT INTO employees SET full_name=:fn, position=:ps, email=:em, hire_date=:hd, status=1";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':fn' => $this->full_name,
                ':ps' => $this->position,
                ':em' => $this->email, // NUEVO
                ':hd' => $this->hire_date // NUEVO
            ]);
        }

        public function update() {
            $sql = "UPDATE employees SET full_name=:fn, position=:ps, email=:em, hire_date=:hd WHERE id=:id"; // NUEVO
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':fn' => $this->full_name,
                ':ps' => $this->position,
                ':em' => $this->email, // NUEVO
                ':hd' => $this->hire_date, // NUEVO
                ':id' => $this->id
            ]);
        }

        public function toggleStatus() {
            $stmt = $this->conn->prepare("UPDATE employees SET status = NOT status WHERE id = ?");
            return $stmt->execute([$this->id]);
        }

        public function delete() {
            $stmt = $this->conn->prepare("DELETE FROM employees WHERE id = ?");
            return $stmt->execute([$this->id]);
        }

        public function countEmployees(){
            $stmt = $this->conn->query("SELECT COUNT(id) AS total FROM employees");
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['total'];
        }
    }
}