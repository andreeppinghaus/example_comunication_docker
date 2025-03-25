<?php

/**
 * 
 * Pra compilar:
 * sudo docker build -t php-app2 .
 * 
 * Para rodar em producao com o copy ativado em Dokerfile: 
 * sudo docker run -v $(pwd)/../in:/usr/src/app/in -it php-app2
 * 
 * Para rodar em desenvolvimento com o copy comentado
 * Este comando vai subir o in e o diretorio atual, refletindo as mudanças no diretorio
 * 
 * sudo docker run -v $(pwd)/../in:/usr/src/app/in -v $(pwd):/usr/src/app php-app2
 */

$path = 'in';
$file = 'exemplo.json';

$full_path= $path.'/'.$file;

$date = new DateTime('now');

if (! file_exists($full_path)) {
    $json = [
        'contador'=> 0,
        'container' => "php-app2",
        "timestamp"=>$date->getTimestamp()  
    ];    

    file_put_contents($full_path, json_encode($json));
}else {
    $json = json_decode(file_get_contents($full_path));
    
    if (is_array($json)) {
        $update = $json;
    } else {
        $update[] = $json;
    }

    $update[]= [
        'contador'=> $json->contador+1,
        'container' => "php-app2",
        "timestamp"=>$date->getTimestamp()  
    ];

    file_put_contents($full_path, json_encode($update));
 
    var_dump($update);
}

