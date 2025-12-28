<?php
require_once 'BaseService.php';
require_once __DIR__ . '/../dao/AuthDao.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthService extends BaseService {
    private $auth_dao;

    public function __construct() {
        $this->auth_dao = new AuthDao();
        parent::__construct(new AuthDao);
    }

    public function get_user_by_email($email) {
        return $this->auth_dao->get_user_by_email($email);
    }

    private function is_valid_email($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    private function is_strong_password($password) {
        if (strlen($password) < 8) return false;
        if (!preg_match('/\d/', $password)) return false;
        return true;
    }

    public function register($entity) {
        $email = trim($entity['email'] ?? '');
        $password = $entity['password'] ?? '';

        if ($email === '' || $password === '') {
            return ['success' => false, 'error' => 'Email and password are required.'];
        }

        if (!$this->is_valid_email($email)) {
            return ['success' => false, 'error' => 'Invalid email format.'];
        }

        if (!$this->is_strong_password($password)) {
            return ['success' => false, 'error' => 'Password must be at least 8 characters and contain at least one number.'];
        }

        $email_exists = $this->auth_dao->get_user_by_email($email);
        if ($email_exists) {
            return ['success' => false, 'error' => 'Email already registered.'];
        }

        $entity['email'] = $email;
        $entity['password'] = password_hash($password, PASSWORD_BCRYPT);

        $new_id = parent::create($entity);
        $user = $this->auth_dao->getById($new_id);

        unset($user['password']);

        return ['success' => true, 'data' => $user];
    }

    public function login($entity) {
        try {
            $email = trim($entity['email'] ?? '');
            $password = $entity['password'] ?? '';

            if ($email === '' || $password === '') {
                return ['success' => false, 'error' => 'Email and password are required.'];
            }

            if (!$this->is_valid_email($email)) {
                return ['success' => false, 'error' => 'Invalid email format.'];
            }

            $user = $this->auth_dao->get_user_by_email($email);

            if (!$user || !isset($user['password']) || !password_verify($password, $user['password'])) {
                return ['success' => false, 'error' => 'Invalid email or password.'];
            }

            unset($user['password']);

            $jwt_payload = [
                'user' => $user,
                'iat' => time(),
                'exp' => time() + (60 * 60 * 24)
            ];

            $token = JWT::encode(
                $jwt_payload,
                Config::JWT_SECRET(),
                'HS256'
            );

            return ['success' => true, 'data' => array_merge($user, ['token' => $token])];
        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Login failed.'];
        }
    }
}
