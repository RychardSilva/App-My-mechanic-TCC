<?php
require("../../connect/connect.php");

session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.html");
    exit;
}

$id_Usuario = isset($_POST['id_Usuario']) ? $_POST['id_Usuario'] : null;
$nomeServico = isset($_POST['nomeServico']) ? $_POST['nomeServico'] : null;
$descricao = isset($_POST['descricao']) ? $_POST['descricao'] : null;
$id_Veiculo = isset($_POST['id_Veiculo']) ? $_POST['id_Veiculo'] : null;

if ($nomeServico && $descricao && $id_Usuario && $id_Veiculo) {
    // Verifica se o id_Veiculo existe na tabela veiculo
    $stmt = $conn->prepare("SELECT idVeiculo FROM veiculo WHERE idVeiculo = ?");
    $stmt->bind_param("i", $id_Veiculo);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();

        // Insere o novo serviço
        $stmt = $conn->prepare("INSERT INTO servico (idServico, id_Usuario, id_Veiculo, nome, descricao) VALUES (NULL, ?, ?, ?, ?)");
        $stmt->bind_param("iiss", $id_Usuario, $id_Veiculo, $nomeServico, $descricao);

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            echo "<script language='javascript' type='text/javascript'>
            alert('Serviço cadastrado com sucesso!');window.location.href='../../login/admJuridica.php';</script>";
        } else {
            echo "Erro ao inserir dados específicos: " . $stmt->error;
        }
    } else {
        echo "Erro: Veículo não encontrado.";
    }
} else {
    echo "Erro: Todos os campos são obrigatórios.";
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
            <label for="id_Usuario">
                ID do Usuário:
                <br>
                <input class="form-control mt-3" type="text" id="id_Usuario" name="id_Usuario">
            </label>
        </div>

        <div class="mb-5">
            <label for="id_Veiculo">
                ID do Veículo:
                <br>
                <input class="form-control mt-3" type="text" id="id_Veiculo" name="id_Veiculo">
            </label>
        </div>

        <div class="mb-5">
            <button type="submit">Enviar</button>
        </div>

        <a href="../../login/admJuridica.php" class="btn btn-secondary">Voltar</a>
    </form>
</body>

</html>