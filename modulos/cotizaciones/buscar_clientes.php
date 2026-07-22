<?php

require_once "../../config/conexion.php";


$buscar = $_GET["buscar"] ?? "";

$pagina = (int)($_GET["pagina"] ?? 1);

if($pagina < 1){

    $pagina = 1;

}


$limite = 7;

$inicio = ($pagina-1)*$limite;



$sql = "
SELECT 
id,
ruc,
razon_social,
direccion
FROM clientes
WHERE estado = 1
AND
(
ruc LIKE ?
OR razon_social LIKE ?
)
ORDER BY razon_social
LIMIT ?,?
";


$stmt = $conexion->prepare($sql);


$texto = "%".$buscar."%";


$stmt->bind_param(
"ssii",
$texto,
$texto,
$inicio,
$limite
);


$stmt->execute();


$resultado = $stmt->get_result();



while($fila=$resultado->fetch_assoc()){


?>

<tr>


<td>

<?= $fila["ruc"] ?>

</td>


<td>

<?= $fila["razon_social"] ?>

</td>


<td>

<?= $fila["direccion"] ?>

</td>


<td>


<button
type="button"
class="btn btn-success btn-sm seleccionarCliente"

data-id="<?= $fila["id"] ?>"

data-nombre="<?= $fila["razon_social"] ?>"

data-ruc="<?= $fila["ruc"] ?>"

>


<i class="bi bi-check"></i>

Seleccionar


</button>


</td>


</tr>


<?php

}

?>