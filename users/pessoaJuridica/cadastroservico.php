<?php
require("../../connect/connect.php");

session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] != true) {
    header("location: login.html");
    exit;
}

// Recupera o ID do usuário logado
$id_Usuario = $_SESSION['idUsuario'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomeServico = isset($_POST['nomeServico']) ? $_POST['nomeServico'] : null;
    $descricao = isset($_POST['descricao']) ? $_POST['descricao'] : null;
    $preco = isset($_POST['preco']) ? $_POST['preco'] : null;
    $tempoDuracao = isset($_POST['tempoExecucao']) ? $_POST['tempoExecucao'] : null;

    // Inicia uma transação
    $conn->begin_transaction();

    try {
        // Seleciona o idOficina da tabela oficina com base no id_Usuario
        $stmt = $conn->prepare("SELECT idOficina FROM oficina WHERE id_Usuario = ?");
        $stmt->bind_param("i", $id_Usuario);

        if (!$stmt->execute()) {
            throw new Exception("Erro ao selecionar idOficina: " . $stmt->error);
        }

        $stmt->bind_result($idOficina);
        if (!$stmt->fetch()) {
            throw new Exception("Oficina não encontrada para o usuário logado.");
        }
        $stmt->close();

        // Insere o serviço na tabela 'servico'
        $stmt = $conn->prepare("INSERT INTO servico (id_Usuario, nome, descricao) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $id_Usuario, $nomeServico, $descricao);

        if (!$stmt->execute()) {
            throw new Exception("Erro ao inserir dados do serviço: " . $stmt->error);
        }

        // Recupera o ID do serviço recém-inserido
        $id_Servico = $stmt->insert_id;

        // Insere os dados na tabela 'oficinaservicos'
        $stmt = $conn->prepare("INSERT INTO oficinaservicos (preco, tempoDuracao, idServico, id_Oficina) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("dsii", $preco, $tempoDuracao, $id_Servico, $idOficina);

        if (!$stmt->execute()) {
            throw new Exception("Erro ao inserir dados na tabela oficinaservicos: " . $stmt->error);
        }

        // Se tudo estiver OK, commit a transação
        $conn->commit();

        // Fecha as declarações e a conexão
        $stmt->close();
        $conn->close();

        // Redireciona com uma mensagem de sucesso
        echo "<script language='javascript' type='text/javascript'>
                alert('Serviço cadastrado com sucesso!');window.location.href='../../login/admJuridica.php';</script>";
    } catch (Exception $e) {
        // Se houver algum erro, desfaz a transação
        $conn->rollback();
        echo "Erro: " . $e->getMessage();
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
    <link rel="stylesheet" href="./pessoajuridica.css">
</head>

<body>
    <h1 class="m-0">Serviços</h1>
    <p class="small">Informe os dados do serviço prestado.</p>

    <form method="post" class="mt-5">
        <div class="mb-5">
            <label for="nomeServico">
                Nome do serviço:
                <br>
                <input class="form-control mt-3" type="text" id="nomeServico" name="nomeServico" required>
            </label>
        </div>

        <div class="mb-5">
            <label for="descricao">
                Descrição do serviço:
                <br>
                <input class="form-control mt-3" type="text" id="descricao" name="descricao" required>
            </label>
        </div>                            

        <div class="mb-5">
            <label for="preco">
                Preço do serviço:
                <br>R$
                <input class="form-control mt-3" type="number" step="0.01" id="preco" name="preco" required>
            </label>
        </div>

        <div class="mb-5">
            <label for="tempoExecucao">
                Tempo de execução:
                <br>
                <input class="form-control mt-3" type="time" id="tempoExecucao" name="tempoExecucao" required>
            </label>
        </div>

        <div class="mb-5">
            <button type="submit">Enviar</button>
        </div>

        <a href="../../login/admJuridica.php" class="btn btn-secondary">Voltar</a>
    </form>
</body>

</html>
