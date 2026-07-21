<?php

require_once "config/conexion.php";
require_once "config/sesion.php";

$usuario = trim($_POST["usuario"]);
$password = md5($_POST["password"]);

$sql = "SELECT * FROM usuarios
        WHERE usuario = ?
        AND password = ?
        AND estado = 1";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("ss", $usuario, $password);
$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows == 1) {

    $datos = $resultado->fetch_assoc();

    $_SESSION["id"] = $datos["id"];
    $_SESSION["nombre"] = $datos["nombre"];
    $_SESSION["usuario"] = $datos["usuario"];
    $_SESSION["rol"] = $datos["rol"];

    header("Location: dashboard.php");
    exit;

} else {

    header("Location: login.php?error=1");
    exit;

}