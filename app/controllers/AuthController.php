<?php
require_once 'app/models/User.php';

class AuthController {
    public function login() {
        //verificar sesion 
        if (session_status() === PHP_SESSION_NONE) session_start();
        header('Content-Type: application/json; charset=utf-8');

        $user = new User();
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($user->login($username, $password)) {
            $u = $user->getUserForSession($username);
            if($u) {
                $_SESSION['user'] = $u;
            }
            echo json_encode(['status' => 'success']);
            exit;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error con el usuario']);
            exit;
        }
    }
}
