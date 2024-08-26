<?php
require_once 'Database.php';

class Task {
    private $db;
    
    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function create($userId, $title, $description, $status) {
        $stmt = $this->db->prepare("INSERT INTO tasks (user_id, title, description, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $userId, $title, $description, $status);
        return $stmt->execute();
    }

    public function getAll($userId) {
        $stmt = $this->db->prepare("SELECT id, title, description, status FROM tasks WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function update($taskId, $title, $description, $status) {
        $stmt = $this->db->prepare("UPDATE tasks SET title = ?, description = ?, status = ? WHERE id = ?");
        $stmt->bind_param("sssi", $title, $description, $status, $taskId);
        return $stmt->execute();
    }

    public function delete($taskId) {
        $stmt = $this->db->prepare("DELETE FROM tasks WHERE id = ?");
        $stmt->bind_param("i", $taskId);
        return $stmt->execute();
    }
}
?>
