<?php
class User {
    private $conn; // Variable para guardar la conexión a la base de datos

    // Constructor: se ejecuta automáticamente al crear el objeto User
    public function __construct($db) {
        $this->conn = $db; // Asigna la conexión recibida a la variable interna
    }

    /**
     * Valida usuario y contraseña en texto plano.
     */
    public function login($username, $password) {
        // Preparamos la consulta SQL. Solo usamos columnas que existen en tu BD
        $query = "SELECT id, username, password FROM users WHERE username = ? LIMIT 1";
        
        try {
            // Preparamos la sentencia para evitar inyecciones SQL
            $stmt = $this->conn->prepare($query);
            // Ejecutamos pasando el nombre de usuario que viene del formulario
            $stmt->execute([$username]);
            // Obtenemos el resultado como un arreglo asociativo
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verificamos: ¿Existe el usuario? ¿La contraseña es IGUAL a la de la BD?
            // Usamos trim() para eliminar espacios accidentales que vimos en tus imágenes
            if ($user && $password === trim($user['password'])) {
                return $user; // Si todo coincide, devolvemos los datos del usuario
            }
            return false; // Si algo falla, devolvemos falso
        } catch (PDOException $e) {
            return false; // Error de base de datos
        }
    }
}