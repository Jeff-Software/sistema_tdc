<?php

require_once "config/config.php";
require_once "config/conexion.php";
require_once "config/sesion.php";

if (isset($_SESSION["usuario"])) {
    header("Location: dashboard.php");
    exit;
} else {
    header("Location: login.php");
    exit;
}