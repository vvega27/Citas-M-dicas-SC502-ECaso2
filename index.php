<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$controller = $_GET['controller'] ?? '';
$action     = $_GET['action'] ?? '';

if ($controller === 'citas') {
    require_once 'app/controllers/CitasController.php';
    $ctrl = new CitasController();

    if ($action === 'list')   { $ctrl->list(); }
    if ($action === 'save')   { $ctrl->save(); }
    if ($action === 'delete') { $ctrl->delete(); }

    http_response_code(404);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['status'=>'error','message'=>'accion no encontrada']);
    exit;
}

if ($controller === 'auth') {
    require_once 'app/models/User.php';
    require_once 'app/controllers/AuthController.php';
    $ctrl = new AuthController();

    if ($action === 'login')  { $ctrl->login(); }
    if ($action === 'logout') { $ctrl->logout(); }

    http_response_code(404);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['status'=>'error','message'=>'accion no encontrada']);
    exit;
}

header('Location: login.php');
exit;
