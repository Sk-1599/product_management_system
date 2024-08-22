<?php
require_once 'Database.php';

class UserModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function isEmailExists($email)
    {
        $query = "SELECT * FROM admin_auth WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    public function registerUser($firstname, $lastname, $email, $password)
    {

        // Check if the email already exists
        if ($this->isEmailExists($email)) {
            echo "<script>alert('Email already exists');
            window.location.href = 'index.php?page=register';
            </script>";
            exit();
        }

        $query = 'INSERT INTO admin_auth (firstname, lastname, email, password, confirm_password) VALUES (:firstname, :lastname, :email, :password, :confirm_password)';
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'password' => $password,
            'confirm_password' => $password
        ]);
    }

    public function loginUser($email, $password)
    {
        $query = 'SELECT * FROM admin_auth WHERE email = :email';
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function emailExists($email)
    {
        $stmt = $this->conn->prepare('SELECT COUNT(*) FROM admin_auth WHERE email = ?');
        $stmt->bindParam(1, $email);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }


    public function isAdmin($email)
    {
        $stmt = $this->conn->prepare('SELECT is_admin FROM admin_auth WHERE email = :email LIMIT 1');
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result['is_admin'];
        }

        return null;
    }
}
