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
    <title>Prestador de Serviço</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../styles/admPrestador.css">
</head>

<body>
    <div class="header">
        <div class="profile" id="profile-container">
            <img src="../imgs/userProfile.png" alt="Perfil" id="profile-icon">
            <div class="dropdown-menu">
                <a href="sair.php">Sair</a>
            </div>
        </div>
        <h1>Olá, <?php echo ($_SESSION["username"]); ?></h1>
        <p>Gerencie seus Serviços</p>
    </div>

    <div class="container-fluid">
        <div class="row justify-content-center text-center">
            <div class="col-md-3 d-flex justify-content-center"> <!-- Adicione 'd-flex' e 'justify-content-center' -->
                <div class="card card-option">
                    <div class="card-body">
                        <a href="../users/prestadorDeServico/cadastroservico.php" class="stretched-link"></a>
                        <img src="../imgs/logging.png" alt="Cadastro de Serviços" class="card-img-top">
                        <h5 class="card-title">Cadastro de Serviços</h5>
                    </div>
                </div>
            </div>

            <div class="col-md-3 d-flex justify-content-center"> <!-- Adicione 'd-flex' e 'justify-content-center' -->
                <div class="card card-option">
                    <div class="card-body">
                        <a href="../users/prestadorDeServico/consultarservico.php" class="stretched-link"></a>
                        <img src="../imgs/school.png" alt="Consulta de Serviços" class="card-img-top">
                        <h5 class="card-title">Consultar meus Serviços</h5>
                    </div>
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
