<?php

require_once "config/config.php";
require_once "config/conexion.php";
require_once "config/sesion.php";

if (isset($_SESSION["usuario"])) {
    header("Location: dashboard.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<title>Iniciar sesión</title>

<link rel="stylesheet" href="assets/css/login.css">

</head>

<body>

<div class="login">

<div class="logo">

    <div class="logo-login">

        ST

    </div>

    <h3>
        STEJOZU
    </h3>

    <small>
        Sistema Comercial
    </small>

</div>

<h2>Iniciar sesión</h2>

<?php
if(isset($_GET["error"])){
    echo "<p style='color:red;text-align:center'>
    Usuario o contraseña incorrectos
    </p>";
}
?>

<form action="validar_login.php" method="POST">

<input
type="text"
name="usuario"
placeholder="Usuario"
required>

<input
type="password"
name="password"
placeholder="Contraseña"
required>

<button type="submit">
Ingresar
</button>

</form>

</div>

</body>

</html>