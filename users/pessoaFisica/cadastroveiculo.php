<?php
require("../../connect/connect.php");

session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.html");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $modelo = isset($_POST['modelo']) ? $_POST['modelo'] : null;
    $ano = isset($_POST['ano']) ? $_POST['ano'] : null;
    $placa = isset($_POST['placa']) ? $_POST['placa'] : null;
    $cor = isset($_POST['cor']) ? $_POST['cor'] : null;    

    if ($modelo && $ano && $placa && $cor) {
        $idUsuario = $_SESSION["idUsuario"];
        $nomeCompleto = null;
        $nomeSocial = null;

        // Verifica se o usuário é pessoa física
        $stmt = $conn->prepare("SELECT nomeCompleto FROM pessoafisica WHERE id_Usuario = ?");
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $stmt->bind_result($nomeCompleto);
        $stmt->fetch();
        $stmt->close();
<<<<<<< HEAD
       
=======

        // Verifica se a placa já existe
        $stmt = $conn->prepare("SELECT idVeiculo FROM veiculo WHERE placa = ?");
        $stmt->bind_param("s", $placa);
        $stmt->execute();
        $stmt->store_result();
>>>>>>> ec6632f0d41c33c4ba95647d8a1f3a537eff9b66

        if ($stmt->num_rows > 0) {
            echo "Erro: A placa já está cadastrada.";
            $stmt->close();
        } else {
            $stmt->close();

            $stmt = $conn->prepare("INSERT INTO veiculo (modelo, ano, placa, cor, id_Usuario, nome_Completo, nome_Social) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sississ", $modelo, $ano, $placa, $cor, $idUsuario, $nomeCompleto, $nomeSocial);

            if ($stmt->execute()) {
                $stmt->close();
                $conn->close();
                echo "<script language='javascript' type='text/javascript'>
                alert('Veículo cadastrado com sucesso!');window.location.href='../../login/admFisica.php';</script>";
            } else {
                echo "Erro ao inserir dados do veículo: " . $stmt->error;
                $stmt->close();
                $conn->close();
            }
        }
    } else {
        echo "Erro: Todos os campos são obrigatórios.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Veículo</title>
</head>

<body>
    <h1 class="m-0">Cadastro de Veículo</h1>
    <p class="small">Informe os dados do veículo.</p>

    <form method="post" class="mt-5">
        <div class="mb-5">
            <label for="modelo">
                Modelo:
                <br>
                <input class="form-control mt-3" type="text" id="modelo" name="modelo">
            </label>
        </div>

        <div class="mb-5">
            <label for="ano">
                Ano:
                <br>
                <input class="form-control mt-3" type="number" id="ano" name="ano">
            </label>
        </div>

        <div class="mb-5">
            <label for="placa">
                Placa:
                <br>
                <input class="form-control mt-3" type="text" id="placa" name="placa">
            </label>
        </div>

        <div class="mb-5">
            <label for="cor">
                Cor:
                <br>
                <input class="form-control mt-3" type="text" id="cor" name="cor">
            </label>
        </div>

        <div class="mb-5">
            <button type="submit">Enviar</button>
        </div>

        
    </table>

    <!-- Botão Voltar -->
    <div style="margin-top: 20px;">
        <a href="../../login/admFisica.php" class="btn btn-secondary">Voltar</a>
    </div>

    </form>
</body>

</html>