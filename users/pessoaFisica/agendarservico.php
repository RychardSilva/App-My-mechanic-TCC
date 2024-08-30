<?php
session_start();

// Verificar se a sessão está iniciada corretamente
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.html");
    exit;
}

require("../../connect/connect.php");

$idUsuario = $_SESSION['idUsuario']; // Assumindo que o ID do usuário está armazenado na sessão

// Obter as cidades únicas do banco de dados
$cidades = [];
$sql = "SELECT DISTINCT cidade FROM endereco";
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idServico = $_POST['idServico'];
    $dataInici = $_POST['data'];
    $horario = $_POST['horario'];
    $placa = $_POST['placa'];

    // Obter id_Oficina e id_Prestador do serviço selecionado
    $sql = "SELECT oficinaservicos.id_Oficina, prestadordeservico.idPrestador 
            FROM servico 
            LEFT JOIN oficinaservicos ON servico.idServico = oficinaservicos.idServico 
            LEFT JOIN prestadordeservico ON servico.id_Usuario = prestadordeservico.id_Usuario 
            WHERE servico.idServico = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idServico);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id_Oficina = $row['id_Oficina'];
        $id_Prestador = $row['idPrestador'];

        // Obter o id_Veiculo com base na placa e idUsuario
        $sql2 = "SELECT idVeiculo FROM veiculo WHERE placa = ? AND id_Usuario = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("si", $placa, $idUsuario);
        $stmt2->execute();
        $result = $stmt2->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $idVeiculo = $row['idVeiculo'];

            if (!is_null($id_Oficina)) {
                // Inserir na tabela agendamentoservicooficina
                $sql1 = "INSERT INTO agendamentoservicooficina (id_Servico, dataInici, id_Oficina) VALUES (?, ?, ?)";
                $stmt1 = $conn->prepare($sql1);
                $stmt1->bind_param("isi", $idServico, $dataInici, $id_Oficina);

                if ($stmt1->execute()) {
                    // Obter o id_AgendamentoServicoOficina inserido
                    $idAgendamentoServicoOficina = $stmt1->insert_id;

                    // Inserir na tabela agendamento
                    $sql3 = "INSERT INTO agendamento (hora, dataAgen, id_Usuario, id_Veiculo, id_AgendamentoOficina) 
                             VALUES (?, ?, ?, ?, ?)";
                    $stmt3 = $conn->prepare($sql3);
                    $stmt3->bind_param("ssiii", $horario, $dataInici, $idUsuario, $idVeiculo, $idAgendamentoServicoOficina);

                    if ($stmt3->execute()) {
                        echo "Agendamento realizado com sucesso!";
                    } else {
                        echo "Erro ao agendar: " . $stmt3->error;
                    }

                    $stmt3->close();
                } else {
                    echo "Erro ao agendar o serviço na oficina: " . $stmt1->error;
                }

                $stmt1->close();
            } else {
                // Inserir diretamente na tabela agendamento com id_AgendamentoOficina como NULL
                $sql3 = "INSERT INTO agendamento (hora, dataAgen, id_Usuario, id_Veiculo, id_AgendamentoOficina) 
                         VALUES (?, ?, ?, ?, NULL)";
                $stmt3 = $conn->prepare($sql3);
                $stmt3->bind_param("ssii", $horario, $dataInici, $idUsuario, $idVeiculo);

                if ($stmt3->execute()) {
                    echo "Agendamento realizado com sucesso!";
                } else {
                    echo "Erro ao agendar: " . $stmt3->error;
                }

                $stmt3->close();
            }
        } else {
            echo "Veículo não encontrado.";
        }

        $stmt2->close();
    } else {
        echo "Oficina ou Prestador não encontrado para o serviço selecionado.";
    }

    $stmt->close();
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
        .form-group input[type="time"],
        .form-group textarea {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
            box-sizing: border-box;
        }

        .form-group textarea {
            resize: vertical;
        }

        .form-group input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .form-group input[type="submit"]:hover {
            background-color: #45a049;
        }

        form {
            width: 40%;
            display: flex;
            flex-direction: column;
            margin-right: 20px; /* Adiciona espaço à direita do formulário */
        }

        .planilha {
            width: 70%; /* Tamanho da tabela */
            margin-left: 20px; /* Adiciona espaço à esquerda da tabela */
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

        .planilha {
            width: 70%; /* Tamanho da tabela aumentado */
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
    
    <h2>Agendamento de Serviços</h2>
    
    <div class="container">
        <form method="post" class="mt-5">
            <div class="form-group">
                <label for="idServico">Id Serviço:</label>
                <input type="number" id="idServico" name="idServico" required>
            </div>
            <div class="form-group">
                <label for="placa">Placa:</label>
                <select id="placa" name="placa" required>
                    <option value="">Selecione uma Placa</option>
                    <?php
                    foreach ($placas as $placa) {
                        echo "<option value='$placa'>$placa</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="data">Data:</label>
                <input type="date" id="data" name="data" required>
            </div>
            <div class="form-group">
                <label for="horario">Horário:</label>
                <input type="time" id="horario" name="horario" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Agendar">
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
                        <th>Nome da Oficina ou Prestador</th>
                        <th>Nome do Serviço</th>
                        <th>Descrição do Serviço</th>
                        <th>Cidade</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $selectedCity = isset($_GET['cidade']) ? $_GET['cidade'] : '';

                    $query = "SELECT servico.idServico, 
                                     COALESCE(pessoajuridica.nomeSocial, prestadordeservico.nomeCompleto) AS nomePrestador, 
                                     servico.nome, 
                                     servico.descricao, 
                                     endereco.cidade
                              FROM servico 
                              LEFT JOIN pessoajuridica ON servico.id_Usuario = pessoajuridica.id_Usuario
                              LEFT JOIN prestadordeservico ON servico.id_Usuario = prestadordeservico.id_Usuario
                              LEFT JOIN endereco ON endereco.id_Usuario = servico.id_Usuario";

                    if (!empty($selectedCity)) {
                        $query .= " WHERE endereco.cidade = '$selectedCity'";
                    }

                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["idServico"] . "</td>";
                            echo "<td>" . $row["nomePrestador"] . "</td>";                            
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
        </div>
    </div>

    <div class="back-button">
        <a href="../../login/admFisica.php" class="btn btn-secondary">Voltar</a>
    </div>

    <script>
        function filterByCity() {
            var city = document.getElementById("cidade").value;
            var url = window.location.href.split('?')[0];
            if (city) {
                window.location.href = url + "?cidade=" + city;
            } else {
                window.location.href = url;
            }
        }
    </script>
</body>
</html>