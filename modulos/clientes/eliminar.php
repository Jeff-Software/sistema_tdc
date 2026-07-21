<?php

require_once "../../config/conexion.php";


$id = intval($_GET["id"] ?? 0);



if($id <= 0){

    die("Cliente inválido");

}



// =========================================
// DESACTIVAR CLIENTE
// =========================================

$sql = "

UPDATE clientes

SET estado = 0

WHERE id = ?

";



$stmt = $conexion->prepare($sql);


$stmt->bind_param(
    "i",
    $id
);



if($stmt->execute()){


    header("Location:index.php");

    exit;


}else{


    echo "Error al eliminar cliente";


}


?>