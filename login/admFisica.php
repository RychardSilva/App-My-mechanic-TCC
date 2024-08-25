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
    <title>Tela Jurídica</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            text-align: center;
        }
        img {
            max-width: 150px;
            margin-bottom: 20px;
        }
        h1 {
            margin-bottom: 20px;
            color: #343a40;
            font-size: 24px; /* Reduzindo o tamanho da fonte */
        }
        .btn-custom {
            width: 90%;
            margin: 10px 0;
            padding: 10px; /* Reduzindo o tamanho dos botões */
            font-size: 14px; /* Ajustando o tamanho da fonte nos botões */
            border-radius: 25px;
            transition: background-color 0.3s;
        }
        .btn-custom:hover {
            background-color: #343a40;
            color: #fff;
        }
        .btn-sair {
            background-color: #dc3545;
            color: #fff;
            margin-top: 20px;
        }
        .btn-veiculo {
            background-color: #28a745;
            color: #fff;
        }
        .btn-servico {
            background-color: #ffc107;
            color: #fff;
        }
        .btn-orcamento {
            background-color: #17a2b8;
            color: #fff;
        }
        .btn-oficinas {
            background-color: #6f42c1;
            color: #fff;
        }
        .btn-diagnostico {
            background-color: #007bff;
            color: #fff;
        }
    
        .btn-dados {
            background-color: #007bff;
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="container">
        <img src="../imgs/Logo.png" alt="Logo">
        <h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION["username"]); ?></h1>
        <a href="../users/pessoaFisica/cadastrarveiculo.php" class="btn btn-custom btn-veiculo">Cadastre seu Veículo</a>
        <a href="../users/pessoaFisica/consultarveiculo.php" class="btn btn-custom btn-veiculo">Consultar Veículos cadastrados</a>
        <a href="../users/pessoaFisica/agendarservico.php" class="btn btn-custom btn-servico">Agendar Serviço</a>
        <a href="../users/pessoaFisica/solicitarorcamento.php" class="btn btn-custom btn-orcamento">Solicitar Orçamento</a>
        <a href="../users/pessoaFisica/consultarservicos.php" class="btn btn-custom btn-servico">Consultar Serviços das Oficinas/Prestadores</a>
        <a href="../users/pessoaFisica/cconsultaroficinas.php" class="btn btn-custom btn-oficinas">Consultar Oficinas/Prestadores Cadastrados</a>
        <a href="../users/pessoaFisica/obterdiagnostico.php" class="btn btn-custom btn-diagnostico">Obter Diagnóstico</a>
        <a href="../users/pessoaFisica/atualizarOficina.php" class="btn btn-custom btn-dados">Atualizar meus dados cadastrais</a>
        <a href="sair.php" class="btn btn-custom btn-sair">Sair</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
