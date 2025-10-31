<?php
require_once 'BaseDao.php';

class UserDao extends BaseDao{
    public function __construct(){
        parent::__construct('users');
    }

    public function createUser($user){
        $data = [
            'name'     => $user['name'],
            'email'    => $user['email'],
            'password' => $user['password'],
            'role'     => $user['role']
        ];
        return $this->insert($data);
    }

    public function getAllUsers(){
        return $this->getAll();
    }

    public function getUserById($id){
        return $this->getById($id);
    }

    public function updateUser($id, $user){
        $data = [
            'name'  => $user['name'],
            'email' => $user['email'],
            'role'  => $user['role']
        ];
        return $this->update($id, $data);
    }

    public function deleteUser($id){
        return $this->delete($id);
    }
}
?>