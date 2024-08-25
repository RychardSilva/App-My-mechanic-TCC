<?php
require("../../connect/connect.php");

session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.html");
    exit;
}

// Capturando os dados do formulário
$hor_funcionamentoInic = isset($_POST['hor_funcInic']) ? $_POST['hor_funcInic'] : null;
$hor_funcionamentoFech = isset($_POST['hor_funcFech']) ? $_POST['hor_funcFech'] : null;
$telefone = isset($_POST['telefone']) ? $_POST['telefone'] : null;
$cidade = isset($_POST['cidade']) ? $_POST['cidade'] : null;
$rua = isset($_POST['rua']) ? $_POST['rua'] : null;
$numero = isset($_POST['numero']) ? $_POST['numero'] : null;
$cep = isset($_POST['cep']) ? $_POST['cep'] : null;
$pais = isset($_POST['pais']) ? $_POST['pais'] : null;
$complemento = isset($_POST['complemento']) ? $_POST['complemento'] : null;

// Selecionando o nomeSocial da tabela pessoajuridica
$stmt = $conn->prepare("SELECT nomeSocial FROM pessoajuridica WHERE id_Usuario = ?");
$stmt->bind_param("i", $_SESSION["idUsuario"]);
$stmt->execute();
$stmt->bind_result($nomeSocial);
$stmt->fetch();
$stmt->close();

if ($hor_funcionamentoInic && $hor_funcionamentoFech && $telefone && $nomeSocial && $cidade && $rua && $numero && $cep && $pais) {
    // Iniciando a transação
    $conn->begin_transaction();

    try {
        // Inserindo na tabela oficina
        $stmt = $conn->prepare("INSERT INTO oficina (horarioFuncionamento_inic, horarioFuncionamento_fin, telefone, id_Usuario, nome) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssis", $hor_funcionamentoInic, $hor_funcionamentoFech, $telefone, $_SESSION["idUsuario"], $nomeSocial);
        $stmt->execute();
        $stmt->close();

        // Inserindo na tabela endereco
        $stmt = $conn->prepare("INSERT INTO endereco (id_Usuario, cep, cidade, rua, complemento, numero, pais) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssss", $_SESSION["idUsuario"], $cep, $cidade, $rua, $complemento, $numero, $pais);
        $stmt->execute();
        $stmt->close();

        // Comitando a transação
        $conn->commit();

        echo "<script language='javascript' type='text/javascript'>
        alert('Dados cadastrados com sucesso!');window.location.href='../../login/admJuridica.php';</script>";
    } catch (Exception $e) {
        // Desfazendo a transação em caso de erro
        $conn->rollback();
        echo "Erro ao inserir dados: " . $e->getMessage();
    }

    $conn->close();
} else {
    echo "Erro: Todos os campos são obrigatórios.";
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro dados Oficina</title>
</head>
<body>
    <h1 class="m-0">Entre com os dados da Oficina</h1>

    <form method="post" class="mt-5">
        
        <div class="mb-5">
            <label for="Cidade">
                Cidade:
                <br>
                <input class="form-control mt-3" type="text" id="cidade" name="cidade">
            </label>
        </div>

        <div class="mb-5">
            <label for="Rua">
                Rua:
                <br>
                <input class="form-control mt-3" type="text" id="rua" name="rua">
            </label>
        </div>
        
        <div class="mb-5">
            <label for="Numero">
                Número:
                <br>
                <input class="form-control mt-3" type="number" id="numero" name="numero">
            </label>
        </div>

        <div class="mb-5">
            <label for="Cep">
                Cep:
                <br>
                <input class="form-control mt-3" type="text" id="cep" name="cep">
            </label>
        </div>

        <div class="mb-5">
            <label for="Pais">
                País:
                <br>
                <input class="form-control mt-3" type="text" id="pais" name="pais">
            </label>
        </div>

        <div class="mb-5">
            <label for="Complemento">
                Complemento:
                <br>
                <input class="form-control mt-3" type="text" id="complemento" name="complemento">
            </label>
        </div>

        <div class="mb-5">
            <label for="Telefone">
                Telefone:
                <br>
                <input class="form-control mt-3" type="text" id="telefone" name="telefone">
            </label>
        </div>
        
        <!-- Subtítulo para horário de funcionamento -->
        <h2>Horário de Funcionamento</h2>

        <div class="mb-5">
            <label for="horario de funcionamento">
                Horário de Início:
                <br>
                <input class="form-control mt-3" type="time" id="hor_funcInic" name="hor_funcInic">
            </label>
        </div>

        <div class="mb-5">
            <label for="horario de fechamento">
                Horário de Fechamento:
                <br>
                <input class="form-control mt-3" type="time" id="hor_funcFech" name="hor_funcFech">
            </label>
        </div>

        <div class="mb-5">
            <button type="submit">Enviar</button>
        </div>
        <br><br>
        <a href="../../login/admJuridica.php" class="btn btn-secondary">Voltar</a>
    </form>
</body>
</html>
