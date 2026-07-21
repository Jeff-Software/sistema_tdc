<?php

session_start();

$_SESSION["clientes_filtros"] = [

    "buscar" => $_POST["buscar"] ?? "",

    "orden" => $_POST["orden"] ?? "az",

    "pagina" => intval($_POST["pagina"] ?? 1)

];