<?php
require_once __DIR__ . '/../dao/UserDao.php';
require_once 'BaseService.php';

class UserService extends BaseService {

    public function __construct() {
        $dao = new UserDao();
        parent::__construct($dao);
    }

    public function createUser($data) {
        if (!isset($data['name']) || trim($data['name']) === '') {
            throw new Exception("Name cannot be empty.");
        }

        if (!isset($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }

        if (!isset($data['password']) || strlen($data['password']) < 6) {
            throw new Exception("Password must be at least 6 characters long.");
        }

        if (!isset($data['role']) || trim($data['role']) === '') {
            $data['role'] = 'user'; 
        }

        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

        return $this->dao->createUser($data);
    }

    public function updateUser($id, $data) {
        if (!isset($data['name']) || trim($data['name']) === '') {
            throw new Exception("Name cannot be empty.");
        }

        if (!isset($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }

        if (!isset($data['role']) || trim($data['role']) === '') {
            throw new Exception("Role cannot be empty.");
        }

        return $this->dao->updateUser($id, $data);
    }

    public function login($email, $password) {
        $users = $this->dao->getAllUsers();

        foreach ($users as $user) {
            if ($user['email'] === $email && password_verify($password, $user['password'])) {
                return $user;
            }
        }

        return null; 
    }
}
?>
