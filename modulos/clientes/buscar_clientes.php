<?php

require_once "../../config/conexion.php";
require_once "../../includes/funciones.php";

$buscar = trim($_GET["buscar"] ?? "");

$orden = $_GET["orden"] ?? "az";

$pagina = intval($_GET["pagina"] ?? 1);

$limite = 6;

$inicio = ($pagina - 1) * $limite;

$sql = "

SELECT *

FROM clientes

WHERE estado = 1

";

if($buscar != ""){

    $buscar = "%".$buscar."%";

    $sql .= "

    AND (

        ruc LIKE ?

        OR razon_social LIKE ?

        OR contacto LIKE ?

        OR telefono LIKE ?

    )

    ";

}

switch($orden){

    case "za":

        $sql .= " ORDER BY razon_social DESC ";

    break;

    case "nuevo":

        $sql .= " ORDER BY id DESC ";

    break;

    case "antiguo":

        $sql .= " ORDER BY id ASC ";

    break;

    default:

        $sql .= " ORDER BY razon_social ASC ";

}

$sql .= "

LIMIT $inicio,$limite

";

$stmt = $conexion->prepare($sql);

if(trim($_GET["buscar"] ?? "") != ""){

    $stmt->bind_param(

        "ssss",

        $buscar,
        $buscar,
        $buscar,
        $buscar

    );

}

$stmt->execute();

$resultado = $stmt->get_result();

if($resultado->num_rows == 0){

?>

<tr>

    <td colspan="5" class="text-center py-4 text-muted">

        <i class="bi bi-search fs-2 d-block mb-2"></i>

        No se encontraron clientes.

    </td>

</tr>

<?php

}else{

    while($fila = $resultado->fetch_assoc()){

?>

<tr>

    <td><?= $fila["ruc"] ?></td>

    <td><?= $fila["razon_social"] ?></td>

    <td><?= $fila["contacto"] ?></td>

    <td><?= formatearTelefono($fila["telefono"]) ?></td>

    <td>

        <a
        href="editar.php?id=<?= $fila["id"] ?>"
        class="btn btn-warning btn-sm">

            Editar

        </a>

        <a
        href="eliminar.php?id=<?= $fila["id"] ?>"
        class="btn btn-danger btn-sm"
        onclick="return confirm('¿Desea desactivar este cliente?');">

            Eliminar

        </a>

    </td>

</tr>

<?php

    }

}

?>