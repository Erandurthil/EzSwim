<?php

function explodeUri($uri)
{
    //trim surrounding slashes
    $turi = trim($uri, '/');
    $parts = explode('/', $turi);
    return $parts;
}
    
?>