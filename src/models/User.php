<?php

namespace Src\Models;

use Src\Helpers\Database;

class User {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function findUserByUsername($username) {
        $this->db->query('SELECT * FROM users WHERE username = :username');
        $this->db->bind(':username', $username);
        return $this->db->single();
    }

    public function setLoginStatus($username, $status) {
        $this->db->query('UPDATE users SET is_logged_in = :status WHERE username = :username');
        $this->db->bind(':status', $status, \PDO::PARAM_BOOL);
        $this->db->bind(':username', $username);
        return $this->db->execute();
    }

    public function logoutAllSessions($username) {
        $this->db->query('UPDATE users SET is_logged_in = false WHERE username = :username');
        $this->db->bind(':username', $username);
        return $this->db->execute();
    }
}
