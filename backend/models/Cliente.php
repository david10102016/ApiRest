<?php
class Cliente {
    private $conn;
    private $table_name = "clientes";

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $telefono;
    public $direccion;
    public $dni;
    public $activo;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE activo = 1 ORDER BY apellido, nombre";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id AND activo = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->nombre = $row['nombre'];
            $this->apellido = $row['apellido'];
            $this->email = $row['email'];
            $this->telefono = $row['telefono'];
            $this->direccion = $row['direccion'];
            $this->dni = $row['dni'];
            return true;
        }
        return false;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                SET nombre=:nombre, apellido=:apellido, email=:email, 
                    telefono=:telefono, direccion=:direccion, dni=:dni";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':apellido', $this->apellido);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':telefono', $this->telefono);
        $stmt->bindParam(':direccion', $this->direccion);
        $stmt->bindParam(':dni', $this->dni);

        return $stmt->execute();
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                SET nombre=:nombre, apellido=:apellido, email=:email, 
                    telefono=:telefono, direccion=:direccion, dni=:dni
                WHERE id=:id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':apellido', $this->apellido);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':telefono', $this->telefono);
        $stmt->bindParam(':direccion', $this->direccion);
        $stmt->bindParam(':dni', $this->dni);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    public function delete() {
        $query = "UPDATE " . $this->table_name . " SET activo = 0 WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }
}
