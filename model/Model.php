<?php
require_once 'Database.php';

class UserModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function registerUser($firstname, $lastname, $email, $password) {
        $query = 'INSERT INTO user_auth (firstname, lastname, email, password, confirm_password) VALUES (:firstname, :lastname, :email, :password, :confirm_password)';
        $stmt = $this->conn->prepare($query);
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $stmt->execute([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'password' => $hashed_password,
            'confirm_password' => $hashed_password
        ]);
    }

    public function loginUser($email, $password) {
        $query = 'SELECT * FROM user_auth WHERE email = :email';
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}
?>
