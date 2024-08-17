<?php
require("../../connect/connect.php");

session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.html");
    exit;
}

$nomeServico = isset($_POST['nomeServico']) ? $_POST['nomeServico'] : null;
$descricao = isset($_POST['descricao']) ? $_POST['descricao'] : null;

if ($nomeServico && $descricao) {
    $stmt = $conn->prepare("INSERT INTO servico (idServico, id_Usuario, id_Veiculo, nome, descricao) VALUES (?,?,?,?,?)");
    $stmt->bind_param("iiiss", $nomeServico, $descricao);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        echo "<script language='javascript' type='text/javascript'>
        alert('Serviço cadastrado com sucesso!');window.location.href='../admJuridica.php';</script>";
    } else {
        echo "Erro ao inserir dados específicos: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Serviço</title>
</head>

<body>
    <h1 class="m-0">Serviços</h1>
    <p class="small">Informe os dados do serviço prestado.</p>

    <form method="post" class="mt-5">
        <div class="mb-5">
            <label for="nomeServico">
                Nome do serviço:
                <br>
                <input class="form-control mt-3" type="text" id="nomeServico" name="nomeServico">
            </label>
        </div>

        <div class="mb-5">
            <label for="descricao">
                Descrição do serviço:
                <br>
                <input class="form-control mt-3" type="text" id="descricao" name="descricao">
            </label>
        </div>

        <div class="mb-5">
            <button type="submit">Enviar</button>
        </div>

        <a href="../admJuridica.php" class="btn btn-secondary">Voltar</a>
    </form>
</body>

</html>