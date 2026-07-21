<?php

require_once "../../config/conexion.php";


$id = $_GET["id"] ?? 0;



if($id == 0){

    header("Location: index.php");

    exit;

}



$sql = "UPDATE articulos SET estado = 0 WHERE id = ?";


$stmt = $conexion->prepare($sql);


$stmt->bind_param("i", $id);



if($stmt->execute()){


    header("Location: index.php");

    exit;


}else{


    echo "Error al eliminar: ".$conexion->error;


}


?>