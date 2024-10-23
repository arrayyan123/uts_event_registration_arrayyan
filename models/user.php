<?php
require_once 'db.php'; 

class User {
    private $db;

    public function __construct() {
        $this->db = new Database(); 
    }

    public function registerUser($name, $email, $password, $role) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); 
        $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
        return $this->db->execute($sql, [$name, $email, $hashedPassword, $role]);
    }    

    public function loginUser($email, $password) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $user = $this->db->fetch($sql, [$email]);
        var_dump($user); 
    
        if ($user) {
            if (password_verify($password, $user['password'])) {
                return $user;
            } else {
                echo "Invalid password";
            }
        } else {
            echo "No user found with that email"; 
        }
        return false;
    }
    
    public function isEmailExists($email) {
        $sql = "SELECT * FROM users WHERE email = ?";
        return $this->db->fetch($sql, [$email]) ? true : false;
    }

    public function getAllUsers() {
        $sql = "SELECT * FROM users";
        return $this->db->fetchAll($sql);
    }
    
    // public function updateProfile($user_id, $name, $email, $profile_pic = null) {
    //     if ($profile_pic) {
    //         $sql = "UPDATE users SET name = ?, email = ?, profile_pic = ? WHERE id = ?";
    //         $params = [$name, $email, $profile_pic, $user_id];
    //     } else {
    //         $sql = "UPDATE users SET name = ?, email = ? WHERE id = ?";
    //         $params = [$name, $email, $user_id];
    //     }
        
    //     return $this->db->execute($sql, $params);
    // }    

    public function updateProfile($user_id, $name, $email, $profile_pic = null) {
        $sql = "UPDATE users SET name = ?, email = ?" . ($profile_pic ? ", profile_pic = ?" : "") . " WHERE id = ?";
        $params = [$name, $email];
        
        if ($profile_pic) {
            $params[] = $profile_pic;
        }
        
        $params[] = $user_id;
        return $this->db->execute($sql, $params);
    }
    
    public function getUserById($user_id) {
        $sql = "SELECT * FROM users WHERE id = ?";
        return $this->db->fetch($sql, [$user_id]);
    }
    

}
