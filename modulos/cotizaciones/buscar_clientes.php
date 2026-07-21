<?php

require_once "../../config/conexion.php";


$buscar = $_GET["buscar"] ?? "";


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
OR
razon_social LIKE ?
)

ORDER BY razon_social ASC

LIMIT 20

";


$stmt = $conexion->prepare($sql);


$buscar = "%".$buscar."%";


$stmt->bind_param(
    "ss",
    $buscar,
    $buscar
);


$stmt->execute();


$resultado = $stmt->get_result();



while($cliente=$resultado->fetch_assoc()){


?>

<tr>


<td>

<?= htmlspecialchars($cliente["ruc"]) ?>

</td>


<td>

<?= htmlspecialchars($cliente["razon_social"]) ?>

</td>


<td>

<?= htmlspecialchars($cliente["direccion"]) ?>

</td>


<td>


<button
type="button"
class="btn btn-success btn-sm seleccionarCliente"

data-id="<?= $cliente["id"] ?>"

data-nombre="<?= $cliente["razon_social"] ?>"

data-ruc="<?= $cliente["ruc"] ?>"

>

Seleccionar

</button>


</td>


</tr>


<?php

}

?>