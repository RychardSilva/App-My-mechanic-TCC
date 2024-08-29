<?php
session_start();

// Verificar se a sessão está iniciada corretamente
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.html");
    exit;
}

require("../../connect/connect.php");

$idUsuario = $_SESSION['idUsuario'];

// Obter as cidades únicas do banco de dados
$cidades = [];
$sql = "SELECT DISTINCT endereco.cidade FROM endereco";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cidades[] = $row['cidade'];
    }
}

// Obter as placas cadastradas para o usuário logado
$placas = [];
$sql = "SELECT placa FROM veiculo WHERE id_Usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $placas[] = $row['placa'];
    }
}

$stmt->close();

$valorTotalOrcamento = 0; // Valor padrão

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tituloOrcamento = $_POST['tituloOrcamento'];
    $dataOrcamento = $_POST['dataOrcamento'];
    $validadeOrcamento = $_POST['validadeOrcamento'];
    $idServico = $_POST['idServico'];

    // Implementação da lógica para salvar o orçamento e calcular o valor total do orçamento...
    // Exemplo fictício de cálculo do valor total:
    $valorTotalOrcamento = 1000; // Valor fictício

    echo "<script>document.getElementById('valorTotalOrcamento').value = " . $valorTotalOrcamento . ";</script>";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento de Serviços</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .container {
            display: flex;
            justify-content: space-between;
            max-width: 1100px;
            margin: 10 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        form {
            width: 40%;
            display: flex;
            flex-direction: column;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 15px;
        }

        .form-group label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input[type="text"],
        .form-group input[type="date"],
        .form-group input[type="number"],
        .form-group input[type="submit"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
            box-sizing: border-box;
        }

        .form-group input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            font-size: 16px;
        }

        .form-group input[type="submit"]:hover {
            background-color: #45a049;
        }

        .planilha {
            width: 70%;
            margin-left: 20px;
        }

        .valor-total {
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .back-button {
            text-align: center;
            margin-top: 20px;
        }

        .back-button a {
            text-decoration: none;
            color: #555;
            font-size: 16px;
        }

        .back-button a:hover {
            text-decoration: underline;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .filter-container {
            margin-bottom: 20px;
            display: flex;
            justify-content: flex-start;
            align-items: center;
        }

        .filter-container label {
            margin-right: 10px;
            font-weight: bold;
        }

        .filter-container select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    
    <h2>Orçamento de Serviços</h2>
    
    <div class="container">
        <form method="post">  
            
            <div class="form-group">
                <label for="dataOrcamento">Data do Orçamento:</label>
                <input type="date" id="dataOrcamento" name="dataOrcamento" required>
            </div>

            <div class="form-group">
                <label for="validadeOrcamento">Validade do Orçamento:</label>
                <input type="date" id="validadeOrcamento" name="validadeOrcamento" required>
            </div>

            <div class="form-group">
                <label for="idServico">Id Serviço:</label>
                <input type="number" id="idServico" name="idServico" required>
            </div>

            <div class="form-group">
                <input type="submit" value="Salvar Orçamento">
            </div>
        </form>

        <div class="planilha">
            <h3>Serviços Disponíveis</h3>
            
            <div class="filter-container">
                <label for="cidade">Filtrar por Cidade:</label>
                <select id="cidade" name="cidade" onchange="filterByCity()">
                    <option value="">Todas</option>
                    <?php
                    foreach ($cidades as $cidade) {
                        echo "<option value='$cidade'>$cidade</option>";
                    }
                    ?>
                </select>
            </div>

            <table id="servicesTable">
                <thead>
                    <tr>
                        <th>idServico</th>
                        <th>Nome da Oficina</th>
                        <th>Nome do Serviço</th>
                        <th>Descrição do Serviço</th>
                        <th>Cidade</th>
                        <th>Valor(R$)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $selectedCity = isset($_GET['cidade']) ? $_GET['cidade'] : '';

                    $query = "SELECT servico.idServico, pessoajuridica.nomeSocial, servico.nome, servico.descricao, endereco.cidade,oficinaservicos.preco
                              FROM servico 
                              JOIN pessoajuridica ON servico.id_Usuario = pessoajuridica.id_Usuario
                              JOIN endereco ON endereco.id_Usuario = servico.id_Usuario
                              LEFT JOIN oficinaservicos ON servico.idServico = oficinaservicos.idServico";
    

                    if (!empty($selectedCity)) {
                        $query .= " WHERE endereco.cidade = '$selectedCity'";
                    }

                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["idServico"] . "</td>";
                            echo "<td>" . $row["nomeSocial"] . "</td>";                            
                            echo "<td>" . $row["nome"] . "</td>";
                            echo "<td>" . $row["descricao"] . "</td>";
                            echo "<td>" . $row["cidade"] . "</td>";                            
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>Nenhum serviço encontrado</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <!-- Nova planilha para orçamentos -->
            <div class="valor-total">
                <h3>Resumo do Orçamento</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Id Orçamento</th>
                            <th>Valor Total do Orçamento</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td> <!-- Substitua pelo idOrcamento real se disponível -->
                            <td id="valorTotalOrcamento">0</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>   

    <div class="back-button">
        <a href="../../login/admFisica.php" class="btn btn-secondary">Voltar</a>
    </div>

    <script>
        function filterByCity() {
            const cidade = document.getElementById('cidade').value;
            window.location.href = "?cidade=" + cidade;
        }
    </script>
</body>
</html>
