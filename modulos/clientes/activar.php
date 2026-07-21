<?php

require_once "../../config/conexion.php";
require_once "../../includes/funciones.php";


$id = intval($_GET["id"]);



$sql = "

UPDATE clientes

SET estado = 1

WHERE id = ?

";


$stmt=$conexion->prepare($sql);


$stmt->bind_param(
"i",
$id
);



$stmt->execute();



header("Location: inactivos.php");

exit;

?>