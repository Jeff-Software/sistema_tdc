<?php

require_once "../../config/conexion.php";


$buscar = trim($_GET["buscar"] ?? "");

$familia = trim($_GET["familia"] ?? "");

$unidad = trim($_GET["unidad"] ?? "");

$pagina = intval($_GET["pagina"] ?? 1);


$porPagina = 6;


// total

$sql = "
SELECT COUNT(*) AS total
FROM articulos
WHERE estado = 1
";


$params=[];
$tipos="";


if($buscar!=""){

$sql.=" AND (
descripcion LIKE ?
OR codigo LIKE ?
)";

$texto="%".$buscar."%";

$params[]=$texto;
$params[]=$texto;

$tipos.="ss";

}


if($familia!=""){

$sql.=" AND familia=?";

$params[]=$familia;

$tipos.="s";

}

if($unidad!=""){

$sql.=" AND unidad=?";

$params[]=$unidad;

$tipos.="s";

}


$stmt=$conexion->prepare($sql);


if(count($params)>0){

$stmt->bind_param($tipos,...$params);

}


$stmt->execute();


$total=$stmt->get_result()->fetch_assoc()["total"];


$totalPaginas=ceil($total/$porPagina);


?>


<nav>

<ul class="pagination justify-content-center">


<?php if($pagina>1){ ?>


<li class="page-item">

<button
type="button"
class="page-link paginaArticulo"
data-pagina="1">

&laquo; Primero

</button>

</li>


<li class="page-item">

<button
type="button"
class="page-link paginaArticulo"
data-pagina="<?= $pagina-1 ?>">

Anterior

</button>

</li>


<?php } ?>


<?php

$mostrar = [];


// primeras páginas

for($i=1; $i<=3 && $i<=$totalPaginas; $i++){

    $mostrar[] = $i;

}


// páginas cercanas a la actual

for($i=$pagina-1; $i<=$pagina+1; $i++){

    if($i>0 && $i<=$totalPaginas){

        $mostrar[] = $i;

    }

}


// últimas páginas

for($i=$totalPaginas-1; $i<=$totalPaginas; $i++){

    if($i>0){

        $mostrar[] = $i;

    }

}


// quitar repetidos

$mostrar = array_unique($mostrar);

sort($mostrar);


$anterior = 0;


foreach($mostrar as $i){


    if($i - $anterior > 1){

?>

<li class="page-item disabled">

<span class="page-link">
...
</span>

</li>


<?php

    }

?>


<li class="page-item <?=($i==$pagina)?'active':''?>">

<button
type="button"
class="page-link paginaArticulo"
data-pagina="<?= $i ?>">

<?= $i ?>

</button>

</li>


<?php

$anterior=$i;


}

?>


<?php if($pagina<$totalPaginas){ ?>


<li class="page-item">

<button
type="button"
class="page-link paginaArticulo"
data-pagina="<?= $pagina+1 ?>">

Siguiente

</button>

</li>


<li class="page-item">

<button
type="button"
class="page-link paginaArticulo"
data-pagina="<?= $totalPaginas ?>">

Último &raquo;

</button>

</li>


<?php } ?>


</ul>

</nav>