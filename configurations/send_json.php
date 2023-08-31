<?php
 
function sendJSON($infos)
{
    
    echo json_encode($infos, JSON_UNESCAPED_UNICODE, JSON_PRETTY_PRINT);
}









?>