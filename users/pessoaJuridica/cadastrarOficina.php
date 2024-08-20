<?php
require("../../connect/connect.php");

session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.html");
    exit;
}

$hor_funcionamentoInic = isset($_POST['hor_funcInic']) ? $_POST['hor_funcInic'] : null;
$hor_funcionamentoFech = isset($_POST['hor_funcFech']) ? $_POST['hor_funcFech'] : null;
$telefone = isset($_POST['telefone']) ? $_POST['telefone'] : null;


if ($hor_funcionamentoInic && $hor_funcionamentoFech && $telefone) {    
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();

        // Insere o novo serviço
        $stmt = $conn->prepare("INSERT INTO oficina (horarioFuncionamentoInic,(horarioFuncionamentoFech, telefone, ) VALUES (NULL, ?, ?)");
        $stmt->bind_param("tts",$hor_funcionamentoInic,$hor_funcionamentoFech, $telefone);

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            echo "<script language='javascript' type='text/javascript'>
            alert('dados cadastrado com sucesso!');window.location.href='../../login/admJuridica.php';</script>";
        } else {
            echo "Erro ao inserir dados específicos: " . $stmt->error;
        }
    } else {
        echo "Erro: Verifique se digitou corretamente";
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
    <h1 class="m-0">Entre com os dados da Oficina</h1>
   

    <form method="post" class="mt-5">
        <div class="mb-5">
            <label for="Telefone">
                Telefone:
                <br>
                <input class="form-control mt-3" type="text" id="telefone" name="telefone">
            </label>
        </div>

        

        <div class="mb-5">
            <label for="horario de funcionamento">
                Horário de Início:
                <br>
                <input class="form-control mt-3" type="time" id="hor_func" name="hor_func">
            </label>
        </div>

        <div class="mb-5">
            
                Horário de Fechamento:
                <br>
                <input class="form-control mt-3" type="time" id="hor_func" name="hor_func">
            </label>
        </div>

        <div class="mb-5">
            <button type="submit">Enviar</button>
        </div>

        <a href="../../login/admJuridica.php" class="btn btn-secondary">Voltar</a>
    </form>
</body>

</html>