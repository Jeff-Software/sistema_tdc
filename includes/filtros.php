<?php

if(session_status() == PHP_SESSION_NONE){

    session_start();

}

function obtenerFiltroClientes(){

    return $_SESSION["clientes_filtros"] ??

    [

        "buscar"=>"",

        "orden"=>"az",

        "pagina"=>1

    ];

}