<?php

require_once "config/sesion.php";

session_unset();

session_destroy();

header("Location: login.php");

exit;