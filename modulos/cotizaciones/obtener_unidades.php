<?php

require_once "../../config/conexion.php";

$sql = "
SELECT DISTINCT unidad
FROM articulos
WHERE estado = 1
ORDER BY unidad
";

$resultado = $conexion->query($sql);

echo '<option value="">Todas las unidades</option>';

while($fila = $resultado->fetch_assoc()){

    echo '<option value="'.$fila["unidad"].'">'
        .$fila["unidad"].
        '</option>';

}