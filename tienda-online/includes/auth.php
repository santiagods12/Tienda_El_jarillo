<?php
require_once __DIR__ . '/database.php';

class Auth {
    private $db;
    private $table = 'usuarios';

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function register($nombre, $email, $password, $rol = 'usuario') {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        
        $query = "INSERT INTO " . $this->table . " 
                  SET nombre = :nombre, email = :email, password = :password, rol = :rol";
        
        $stmt = $this->db->prepare($query);
        
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':rol', $rol);
        
        return $stmt->execute();
    }

    public function login($email, $password, $user_type = null) {
        $query = "SELECT id, nombre, password, rol FROM " . $this->table . " WHERE email = ? LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$email]);
        
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if(password_verify($password, $row['password'])) {
                // Verificación adicional del tipo de usuario si se especificó
                if($user_type !== null) {
                    if(($user_type == 'admin' && $row['rol'] == 'admin') || 
                       ($user_type == 'user' && $row['rol'] == 'usuario')) {
                        return [
                            'id' => $row['id'],
                            'nombre' => $row['nombre'],
                            'email' => $email,
                            'rol' => $row['rol']
                        ];
                    }
                    return false; // No coincide el tipo de usuario
                }
                
                // Si no se especificó tipo de usuario, retornar normalmente
                return [
                    'id' => $row['id'],
                    'nombre' => $row['nombre'],
                    'email' => $email,
                    'rol' => $row['rol']
                ];
            }
        }
        
        return false;
    }

    public function emailExists($email) {
        $query = "SELECT id FROM " . $this->table . " WHERE email = ? LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$email]);
        return $stmt->rowCount() > 0;
    }

    // Nuevo método para verificar rol específico
    public function checkRole($email, $role) {
        $query = "SELECT id FROM " . $this->table . " WHERE email = ? AND rol = ? LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$email, $role]);
        return $stmt->rowCount() > 0;
    }
}
?>