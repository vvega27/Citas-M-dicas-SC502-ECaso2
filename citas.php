<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user'])) { header('Location: login.php'); exit; }
$rol = $_SESSION['user']['rol'] ?? '';
$nombre  = $_SESSION['user']['nombre'] ?? ''; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Citas Medicas</title>
  <link rel="stylesheet" href="/css/styles.css">
</head>

<body data-role="<?php echo htmlspecialchars($rol); ?>">
<header>
    <h1>Lista de citas</h1>
    <div class="topbar">
      <div class="user">
        Usuario: <strong><?php echo htmlspecialchars($nombre); ?></strong> (<?php echo htmlspecialchars($rol); ?>)
      </div>
      <button id="btnLogout">Salir</button>
    </div>
</header>

  <main>
    <button id="btnNew">Nueva cita</button>

    <table id="tbl" class="grid">
      <thead>
        <tr>
          <th>Paciente</th>
          <th>Fecha</th>
          <th>Hora</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </main>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="/js/citas.js"></script>
</body>
</html>