<?php 

class User {

    private $db;
    //constructor

    public function __construct() {

        $this->db = new Db;
    }

    // R3EGISTER A USER

    public function register($data) {
        $this->db->query("INSERT INTO users (name, email, password) VALUES(:name, :email, :password)");

        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);

        // EXECUTE
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // LOGIN A USER
    public function login($email, $password) {

        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        $row = $this->db->singleData();

        $hashed_password = $row->password;
        if(password_verify($password, $hashed_password)) {
            return $row;
        } else {
            return false;
        }
    }
    // find a user by email
    public function FindUserByEmail($email) {
        $this->db->query("SELECT * FROM users WHERE email = :email");
        $this->db->bind(':email', $email);
        
        $row = $this->db->singleData();

        if($this->db->rowcount() > 0) {
            return true;
        } else {
            return false;
        }
    }
}