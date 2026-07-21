<?php

function formatearTelefono($telefono){

    $telefono = preg_replace('/\D/', '', $telefono);

    if(strlen($telefono) == 9){

        return substr($telefono,0,3)." ".
               substr($telefono,3,3)." ".
               substr($telefono,6,3);

    }

    return $telefono;

}

function limpiarTelefono($telefono){

    return substr(
        preg_replace('/\D/', '', $telefono),
        0,
        9
    );

}