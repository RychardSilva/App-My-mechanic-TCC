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

        // Verifica se a placa já existe
        $stmt = $conn->prepare("SELECT idVeiculo FROM veiculo WHERE placa = ?");
        $stmt->bind_param("s", $placa);
        $stmt->execute();
        $stmt->store_result();

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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 400px;
        }
        h1 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }
        label {
            display: block;
            margin-bottom: 10px;
            color: #555;
        }
        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            border: none;
            border-radius: 4px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        .btn-secondary {
            display: block;
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            background-color: #6c757d;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }

        /* Responsividade para dispositivos móveis */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
                margin-top: 15%; /* Aumenta a margem superior em telas menores */
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 15px;
                margin-top: 20%; /* Aumenta ainda mais a margem superior para telas pequenas */
            }
            button {
                font-size: 14px; /* Ajuste do tamanho da fonte em telas pequenas */
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Cadastro de Veículo</h1>
        <p>Informe os dados do veículo.</p>

        <form method="post">
            <label for="modelo">
                Modelo:
                <input type="text" id="modelo" name="modelo">
            </label>

            <label for="ano">
                Ano:
                <input type="number" id="ano" name="ano">
            </label>

            <label for="placa">
                Placa:
                <input type="text" id="placa" name="placa">
            </label>

            <label for="cor">
                Cor:
                <input type="text" id="cor" name="cor">
            </label>

            <button type="submit">Enviar</button>
        </form>

        <a href="../../login/admFisica.php" class="btn btn-secondary">Voltar</a>
    </div>
</body>

</html>