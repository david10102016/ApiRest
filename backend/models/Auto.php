<?php
class Auto {
    private $conn;
    private $table_name = "autos";

    public $id;
    public $marca;
    public $modelo;
    public $año;
    public $precio;
    public $color;
    public $kilometraje;
    public $combustible;
    public $transmision;
    public $descripcion;
    public $activo;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE activo = 1 ORDER BY id DESC";
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
            $this->marca = $row['marca'];
            $this->modelo = $row['modelo'];
            $this->año = $row['año'];
            $this->precio = $row['precio'];
            $this->color = $row['color'];
            $this->kilometraje = $row['kilometraje'];
            $this->combustible = $row['combustible'];
            $this->transmision = $row['transmision'];
            $this->descripcion = $row['descripcion'];
            return true;
        }
        return false;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                SET marca=:marca, modelo=:modelo, año=:año, precio=:precio, 
                    color=:color, kilometraje=:kilometraje, combustible=:combustible, 
                    transmision=:transmision, descripcion=:descripcion";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':marca', $this->marca);
        $stmt->bindParam(':modelo', $this->modelo);
        $stmt->bindParam(':año', $this->año);
        $stmt->bindParam(':precio', $this->precio);
        $stmt->bindParam(':color', $this->color);
        $stmt->bindParam(':kilometraje', $this->kilometraje);
        $stmt->bindParam(':combustible', $this->combustible);
        $stmt->bindParam(':transmision', $this->transmision);
        $stmt->bindParam(':descripcion', $this->descripcion);

        return $stmt->execute();
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                SET marca=:marca, modelo=:modelo, año=:año, precio=:precio, 
                    color=:color, kilometraje=:kilometraje, combustible=:combustible, 
                    transmision=:transmision, descripcion=:descripcion
                WHERE id=:id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':marca', $this->marca);
        $stmt->bindParam(':modelo', $this->modelo);
        $stmt->bindParam(':año', $this->año);
        $stmt->bindParam(':precio', $this->precio);
        $stmt->bindParam(':color', $this->color);
        $stmt->bindParam(':kilometraje', $this->kilometraje);
        $stmt->bindParam(':combustible', $this->combustible);
        $stmt->bindParam(':transmision', $this->transmision);
        $stmt->bindParam(':descripcion', $this->descripcion);
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