<?php

require_once "../../config/conexion.php";


$buscar = $_GET["buscar"] ?? "";


$limite = 7;


$sql = "

SELECT COUNT(*) total

FROM clientes

WHERE estado = 1

AND

(
ruc LIKE ?
OR razon_social LIKE ?
)

";


$stmt=$conexion->prepare($sql);


$texto="%".$buscar."%";


$stmt->bind_param(
"ss",
$texto,
$texto
);


$stmt->execute();


$total =
$stmt->get_result()
->fetch_assoc()["total"];



$totalPaginas =
ceil($total/$limite);



if($totalPaginas<=1){

    exit;

}



echo '

<nav>

<ul class="pagination justify-content-center">

';


for($i=1;$i<=$totalPaginas;$i++){


echo '

<li class="page-item">

<button
type="button"
class="page-link paginaClienteCotizacion"
data-pagina="'.$i.'">

'.$i.'

</button>

</li>

';


}


echo '

</ul>

</nav>

';

?>