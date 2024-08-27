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
    <title>Físico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../styles/admFisica.css">
</head>

<body>
    <div class="header">
        <div class="profile" id="profile-container">
            <img src="../imgs/userProfile.png" alt="Perfil" id="profile-icon">
            <div class="dropdown-menu">
                <a href="sair.php">Sair</a>
            </div>
        </div>
        <h1>Bem-vindo, <?php echo ($_SESSION["username"]); ?></h1>
        <p>Mantenha as manutenções do seus veículos em dia</p>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="card card-option">
                    <div class="card-body">
                        <a href="../users/pessoaFisica/cadastroveiculo.php" class="stretched-link"></a>
                        <img src="../imgs/registrocarro.png" alt="Cadastre seu Veículo" class="card-img-top">
                        <h5 class="card-title">Cadastre seu Veículo</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-option">
                    <div class="card-body">
                        <a href="../users/pessoaFisica/consultarveiculo.php" class="stretched-link"></a>
                        <img src="../imgs/find-car.png" alt="Consultar Veículos cadastrados" class="card-img-top">
                        <h5 class="card-title">Consultar Veículos cadastrados</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-option">
                    <div class="card-body">
                        <a href="../users/pessoaFisica/agendarservico.php" class="stretched-link"></a>
                        <img src="../imgs/schedule.png" alt="Agendar Serviço" class="card-img-top">
                        <h5 class="card-title">Agendar Serviço</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-option">
                    <div class="card-body">
                        <a href="../users/pessoaFisica/solicitarorcamento.php" class="stretched-link"></a>
                        <img src="../imgs/quote-request.png" alt="Solicitar Orçamento" class="card-img-top">
                        <h5 class="card-title">Solicitar Orçamento</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-option">
                    <div class="card-body">
                        <a href="../users/pessoaFisica/consultarservicos.php" class="stretched-link"></a>
                        <img src="../imgs/product.png" alt="Consultar Serviços das Oficinas/Prestadores" class="card-img-top">
                        <h5 class="card-title">Consultar Serviços das Oficinas/Prestadores</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-option">
                    <div class="card-body">
                        <a href="../users/pessoaFisica/cconsultaroficinas.php" class="stretched-link"></a>
                        <img src="../imgs/school.png" alt="Consultar Oficinas/Prestadores Cadastrados" class="card-img-top">
                        <h5 class="card-title">Consultar Oficinas/Prestadores Cadastrados</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-option">
                    <div class="card-body">
                        <a href="../users/pessoaFisica/obterdiagnostico.php" class="stretched-link"></a>
                        <img src="../imgs/identify.png" alt="Obter Diagnóstico" class="card-img-top">
                        <h5 class="card-title">Obter Diagnóstico</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-option">
                    <div class="card-body">
                        <a href="../users/pessoaFisica/atualizarOficina.php" class="stretched-link"></a>
                        <img src="../imgs/data-processing.png" alt="Atualizar meus dados cadastrais" class="card-img-top">
                        <h5 class="card-title">Atualizar meus dados cadastrais</h5>
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