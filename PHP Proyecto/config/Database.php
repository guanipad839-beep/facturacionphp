<?php
/**
 * Clase Database: Encargada de establecer la conexión con MySQL usando PDO.
 */
class Database {
    // Credenciales de conexión
    private $host = "localhost";
    private $db_name = "gestion_empleados";
    private $username = "root";
    private $password = "";
    public $conn;

    /**
     * Crea y retorna la conexión a la base de datos.
     */
    public function getConnection() {
        $this->conn = null;
        try {
            // DSN: Data Source Name. Define el tipo de base de datos, host y nombre de la BD.
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4";
            
            // Creamos la instancia de PDO.
            $this->conn = new PDO($dsn, $this->username, $this->password);
            
            // Configuramos PDO para que lance excepciones en caso de errores de SQL.
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        } catch(PDOException $exception) {
            // Si la conexión falla, se registra en el log del servidor.
            error_log("Error de conexión: " . $exception->getMessage());
        }
        return $this->conn;
    }
}