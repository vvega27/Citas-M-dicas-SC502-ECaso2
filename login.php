<?php
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Login - Citas Medicas</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<div class="login-container">
    <h2>Iniciar sesión</h2>
    <form id="formLogin" method="post" action="index.php?controller=auth&action=login">
        <label>Usuario:</label>
        <input type="text" name="username" required>

        <label>Contraseña:</label>
        <input type="password" name="password" required>

        <button type="submit">Ingresar</button>
        <p id="msg" style="color:red"></p>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="/js/login.js"></script>
</body>
</html>
