<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'app/config/db.php';

class CitasController {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
        $this->db->set_charset('utf8mb4');
        header('Content-Type: application/json; charset=utf-8');
    }

    // GET: lista todas las citas
    public function list() {
        $res = $this->db->query("SELECT id, nombre_paciente, fecha, hora, estado FROM citas ORDER BY fecha, hora");
        $rows = [];
        while ($r = $res->fetch_assoc()) { $rows[] = $r; }
        echo json_encode(['status'=>'ok','data'=>$rows]); exit;
    }

    // POST: crear (id=0) o actualizar (id>0)
    public function save() {
        if (!isset($_SESSION['user'])) { http_response_code(401); echo json_encode(['status'=>'error','message'=>'no auth']); exit; }

        $id = (int)($_POST['id'] ?? 0);
        $np = trim($_POST['nombre_paciente'] ?? '');
        $fe = trim($_POST['fecha'] ?? '');
        $ho = trim($_POST['hora'] ?? '');
        $es = trim($_POST['estado'] ?? 'pendiente');
        $usr = $_SESSION['user']['usuario'] ?? 'sistema';
        $rol = $_SESSION['user']['rol'] ?? '';

        if ($np === '' || $fe === '' || $ho === '') {
            http_response_code(400); echo json_encode(['status'=>'error','message'=>'datos incompletos']); exit;
        }

        if ($id === 0) {
            // crear: admin y recepcionista
            $st = $this->db->prepare("INSERT INTO citas (nombre_paciente, fecha, hora, estado, nombre_usuario) VALUES (?,?,?,?,?)");
            $st->bind_param('sssss', $np, $fe, $ho, $es, $usr);
            $st->execute();
            echo json_encode(['status'=>'ok','id'=>$st->insert_id]); exit;
        }

        // actualizar: solo admin
        if ($rol !== 'admin') { http_response_code(403); echo json_encode(['status'=>'error','message'=>'solo admin puede editar']); exit; }

        $st = $this->db->prepare("UPDATE citas SET nombre_paciente=?, fecha=?, hora=?, estado=? WHERE id=?");
        $st->bind_param('ssssi', $np, $fe, $ho, $es, $id);
        $st->execute();
        echo json_encode(['status'=>'ok','id'=>$id]); exit;
    }

    // POST: eliminar (solo admin)
    public function delete() {
        if (!isset($_SESSION['user'])) { http_response_code(401); echo json_encode(['status'=>'error','message'=>'no auth']); exit; }
        if (($_SESSION['user']['rol'] ?? '') !== 'admin') { http_response_code(403); echo json_encode(['status'=>'error','message'=>'solo admin puede eliminar']); exit; }

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) { http_response_code(400); echo json_encode(['status'=>'error','message'=>'id invalido']); exit; }

        $st = $this->db->prepare("DELETE FROM citas WHERE id=?");
        $st->bind_param('i', $id);
        $st->execute();
        echo json_encode(['status'=>'ok']); exit;
    }
}
