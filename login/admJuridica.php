<?php
session_start();

// Verificar se a sessão está iniciada corretamente
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.html");
    exit;
}


?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jurídico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../styles/admJuridica.css">
</head>

<body>
    <div class="header">
        <div class="profile" id="profile-container">
            <img src="https://via.placeholder.com/40" alt="Perfil" id="profile-icon">
            <ul class="dropdown-menu" aria-labelledby="profile-icon">
                <li><a class="dropdown-item" href="../users/pessoaJuridica/perfil.php">Meus Dados</a></li>
                <li><a class="dropdown-item" href="sair.php">Sair</a></li>
            </ul>
        </div>
        <h1>Olá, <?php echo ($_SESSION["username"]); ?></h1>
        <p>Gerencie seus Serviços</p>
    </div>

    <div class="container-fluid">
        <div class="row text-center">
            <div class="col-md-3">
                <div class="card card-option">
                    <div class="card-body">
                        <a href="../users/pessoaJuridica/cadastroservico.php" class="stretched-link"></a>
                        <img src="https://via.placeholder.com/150" alt="Cadastro de Serviços" class="card-img-top">
                        <h5 class="card-title">Cadastro de Serviços</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-option">
                    <div class="card-body">
                        <a href="../users/pessoaJuridica/cadastrarOficina.php" class="stretched-link"></a>
                        <img src="https://via.placeholder.com/150" alt="Cadastrar Oficina" class="card-img-top">
                        <h5 class="card-title">Cadastrar Oficina</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-option">
                    <div class="card-body">
                        <a href="../users/pessoaJuridica/consultaVeiculos.php" class="stretched-link"></a>
                        <img src="https://via.placeholder.com/150" alt="Consultar Veículos Cadastrados" class="card-img-top">
                        <h5 class="card-title">Consultar Veículos Cadastrados</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-option">
                    <div class="card-body">
                        <a href="../users/pessoaJuridica/cadastroveiculo.php" class="stretched-link"></a>
                        <img src="https://via.placeholder.com/150" alt="Cadastre seus Veículos" class="card-img-top">
                        <h5 class="card-title">Cadastre seus Veículos</h5>
                    </div>
                </div>
            </div>
        </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="../login/selecionaPerfil.js"></script>
</body>

</html>