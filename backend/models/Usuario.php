<?php
class Usuario {
    private $conn;
    private $table_name = "usuarios";

    public $id;
    public $nombre;
    public $email;
    public $password;
    public $rol;
    public $activo;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login() {
        $query = "SELECT id, nombre, email, password, rol FROM " . $this->table_name . " WHERE email = :email AND activo = 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if(password_verify($this->password, $row['password'])) {
                $this->id = $row['id'];
                $this->nombre = $row['nombre'];
                $this->rol = $row['rol'];
                return true;
            }
        }
        return false;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET nombre=:nombre, email=:email, password=:password, rol=:rol";
        
        $stmt = $this->conn->prepare($query);
        
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':rol', $this->rol);

        return $stmt->execute();
    }
}