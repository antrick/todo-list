<?php
require_once 'Database.php';

class User {
    private $db;
    
    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function userExists($username) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        return $count > 0;
    }

    public function register($username, $password) {
        if ($this->userExists($username)) {
            return false; // El usuario existe
        }
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $passwordHash);
        return $stmt->execute();
    }

    public function login($username, $password) {
        $query = "SELECT id, password_hash FROM users WHERE username = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            // Verifica la contraseña
            if (password_verify($password, $user['password_hash'])) {
                // regresa el id
                return $user['id'];
            }
        }
        return false;
    }
}
?>