<?php

require_once "../../config/conexion.php";


$buscar = trim($_GET["buscar"] ?? "");

$orden = $_GET["orden"] ?? "az";

$sql = "

SELECT COUNT(*) total

FROM clientes

WHERE estado = 1

";


if($buscar != ""){

    $buscar="%".$buscar."%";


    $sql .= "

    AND (

        ruc LIKE ?

        OR razon_social LIKE ?

        OR contacto LIKE ?

        OR telefono LIKE ?

    )

    ";


}


$stmt=$conexion->prepare($sql);



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


$total=$stmt->get_result()->fetch_assoc()["total"];



$limite= 6;


$totalPaginas=ceil($total/$limite);



$pagina=intval($_GET["pagina"] ?? 1);



if($totalPaginas<=1){

exit;

}



$paginaActual = intval($_GET["pagina"] ?? 1);

// Primer botón

if($paginaActual > 1){

?>

<button
class="btn btn-sm btn-outline-secondary mx-1 paginaCliente"
data-pagina="1">

<i class="bi bi-chevron-double-left"></i>

</button>

<?php

}

// Ventana de páginas

$inicio = max(1, $paginaActual - 2);
$fin    = min($totalPaginas, $paginaActual + 2);

// ...

if($inicio > 1){

    echo "<span class='mx-2'>...</span>";

}

for($i=$inicio;$i<=$fin;$i++){

?>

<button
class="btn btn-sm <?= $i==$paginaActual ? 'btn-success' : 'btn-outline-success' ?> mx-1 paginaCliente"
data-pagina="<?= $i ?>">

<?= $i ?>

</button>

<?php

}

if($fin < $totalPaginas){

    echo "<span class='mx-2'>...</span>";

}

// Último botón

if($paginaActual < $totalPaginas){

?>

<button
class="btn btn-sm btn-outline-secondary mx-1 paginaCliente"
data-pagina="<?= $totalPaginas ?>">

<i class="bi bi-chevron-double-right"></i>

</button>

<?php

}