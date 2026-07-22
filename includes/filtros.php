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

// ================================
// ARTÍCULOS
// ================================

function guardarFiltroArticulos(
    $buscar,
    $orden,
    $pagina
){

    $_SESSION["articulos_filtros"] = [

        "buscar" => $buscar,

        "orden" => $orden,

        "pagina" => $pagina

    ];

}

function obtenerFiltroArticulos(){

    return $_SESSION["articulos_filtros"] ??

    [

        "buscar" => "",

        "orden" => "recientes",

        "pagina" => 1

    ];

}