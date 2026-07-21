<?php

require_once "../../includes/layout_inicio.php";

?>

<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/articulos_inactivos.css">

<?php

require_once "../../config/conexion.php";


$sql = "SELECT *
        FROM articulos
        WHERE estado = 0
        ORDER BY familia, descripcion";


$resultado = $conexion->query($sql);


?>


<div class="inactivos-header">


<div>

<h2>

<i class="bi bi-eye-slash"></i>

Artículos inactivos

</h2>

<p class="text-muted mb-0">

Listado de artículos desactivados.

</p>

</div>



<a href="index.php" class="btn btn-outline-secondary">

<i class="bi bi-arrow-left"></i>

Volver

</a>


</div>

<div class="articulos-inactivos-card">

<table class="table table-hover align-middle">

<thead>

<tr>

<th>#</th>
<th>Familia</th>
<th>Descripción</th>
<th>Unidad</th>
<th>Acción</th>

</tr>

</thead>



<tbody>


<?php

$n=1;


if($resultado->num_rows == 0){

?>


<tr>

<td colspan="5" class="text-center">

No hay artículos inactivos.

</td>

</tr>


<?php

}else{


while($fila = $resultado->fetch_assoc()){


?>


<tr>


<td>
<?= $n++ ?>
</td>


<td>
<?= $fila["familia"] ?>
</td>


<td>
<?= $fila["descripcion"] ?>
</td>


<td>
<?= $fila["unidad"] ?>
</td>



<td>


<a href="restaurar.php?id=<?=$fila["id"]?>"
class="btn btn-restaurar btn-sm"
onclick="return confirm('¿Restaurar este artículo?');">


<i class="bi bi-arrow-counterclockwise"></i>

Restaurar

</a>


</td>


</tr>



<?php

}

}

?>


</tbody>


</table>

</div>

<?php

require_once "../../includes/layout_fin.php";

?>