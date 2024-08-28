<?php
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $bd = 'my_mechanic';
    $port = 3306;

    $conn = mysqli_connect($host, $user, $password, $bd, $port);

    if(!$conn){
        echo 'Erro ao conectar ao banco de dados';
    }
    else{
        echo 'Conexão efetuada realizada com sucesso';
    }
?>
