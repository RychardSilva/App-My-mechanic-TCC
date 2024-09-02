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
    $dataOrcamento = $_POST['dataOrcamento'];
    $validadeOrcamento = $_POST['validadeOrcamento'];
    $idServicos = $_POST['idServicos'];

    // Verificar se a data de orçamento é maior ou igual à data atual
    $dataAtual = date('Y-m-d');
    if ($dataOrcamento < $dataAtual) {
        echo "Erro: A data do orçamento não pode ser menor que a data atual.";
        exit;
    }

    // Verificar se a data de validade é maior ou igual à data do orçamento
    if ($validadeOrcamento < $dataOrcamento) {
        echo "Erro: A data de validade não pode ser menor que a data do orçamento.";
        exit;
    }

    // Inserir na tabela orcamento
    $sql = "INSERT INTO orcamento (id_Usuario, dataOrcamento, validadeOrcamento) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $idUsuario, $dataOrcamento, $validadeOrcamento);

    if ($stmt->execute()) {
        $idOrcamento = $stmt->insert_id;

        // Calcular o valor total dos serviços
        foreach ($idServicos as $idServico) {
            $sql3 = "SELECT preco, idOficinaServ FROM oficinaservicos WHERE idServico = ?";
            $stmt3 = $conn->prepare($sql3);
            $stmt3->bind_param("i", $idServico);
            $stmt3->execute();
            $result = $stmt3->get_result();
            $row = $result->fetch_assoc();
            if ($row) {
                $preco = $row['preco'];
                $idOficinaServ = $row['idOficinaServ']; // Captura o valor de idOficinaServ
                $valorTotalOrcamento += $preco;

                // Inserir o valor total e idOficinaServ na tabela orcamentooficinaservico
                $sql2 = "INSERT INTO orcamentooficinaservico (id_Orcamento, valorTotal, id_OficinaServico) VALUES (?, ?, ?)";
                $stmt2 = $conn->prepare($sql2);
                $stmt2->bind_param("idi", $idOrcamento, $valorTotalOrcamento, $idOficinaServ);
                $stmt2->execute();
                $stmt2->close();
            }
            $stmt3->close();
        }
    } else {
        echo "Erro ao inserir orçamento: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orçamento de Serviços</title>
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
            justify-content: center;
            align-items: center;
            flex-direction: column;
            max-width: 1100px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        form {
            width: 100%;
            max-width: 500px;
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
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
        .form-group input[type="submit"],
        .form-group select {
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
            width: 100%;
            max-width: 800px;
            margin-top: 20px;
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
            background-color: #ddd;
            padding: 10px 20px;
            border-radius: 4px;
        }

        .back-button a:hover {
            background-color: #ccc;
        }

        #servicosList {
            list-style-type: none;
            padding: 0;
        }

        #servicosList li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 5px;
            background-color: #f9f9f9;
        }

        #servicosList li button {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }

        #servicosList li button:hover {
            background-color: #ff1a1a;
        }
    </style>
    <script>
        function addServico() {
            var idServico = document.getElementById('idServico').value;
            if (idServico) {
                var ul = document.getElementById('servicosList');
                var li = document.createElement('li');
                li.appendChild(document.createTextNode(idServico + " "));
               
                var deleteButton = document.createElement('button');
                deleteButton.type = 'button';
                deleteButton.innerText = 'Excluir';
                deleteButton.onclick = function() {
                    ul.removeChild(li);
                };
                li.appendChild(deleteButton);

                ul.appendChild(li);

                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'idServicos[]';
                input.value = idServico;
                ul.appendChild(input);

                document.getElementById('idServico').value = '';
            }
        }

        function filterByCity() {
            var city = document.getElementById('cidade').value;
            if (city === "") {
                window.location.href = 'solicitarorcamento.php';
            } else {
                window.location.href = 'solicitarorcamento.php?cidade=' + city;
            }
        }

        function validateForm() {
            var dataOrcamento = document.getElementById("dataOrcamento").value;
            var validadeOrcamento = document.getElementById("validadeOrcamento").value;
            var dataAtual = new Date().toISOString().split("T")[0]; // Data atual no formato yyyy-mm-dd

            if (dataOrcamento < dataAtual) {
                alert("A data do orçamento não pode ser menor que a data atual.");
                return false;
            }

            if (validadeOrcamento < dataOrcamento) {
                alert("A data de validade não pode ser menor que a data do orçamento.");
                return false;
            }

            return true;
        }
    </script>
</head>
<body>
    <h2>Solicitar Orçamento</h2>
    <div class="container">
        <form method="POST" action="" onsubmit="return validateForm();">
            <div class="form-group">
                <label for="dataOrcamento">Data do Orçamento:</label>
                <input type="date" id="dataOrcamento" name="dataOrcamento" required>
            </div>
            <div class="form-group">
                <label for="validadeOrcamento">Validade do Orçamento:</label>
                <input type="date" id="validadeOrcamento" name="validadeOrcamento" required>
            </div>
            <div class="form-group">
                <label for="placa">Placa do Veículo:</label>
                <input type="text" id="placa" name="placa" list="placas" required>
                <datalist id="placas">
                    <?php foreach ($placas as $placa) : ?>
                        <option value="<?php echo $placa; ?>">
                    <?php endforeach; ?>
                </datalist>
            </div>
            <div class="form-group">
                <label for="idServico">Adicionar Serviço:</label>
                <input type="text" id="idServico" name="idServico">
                <button type="button" onclick="addServico()">Adicionar</button>
            </div>
            <ul id="servicosList"></ul>
            <input type="submit" value="Solicitar Orçamento">
        </form>
        <div class="planilha">
            <div class="filter-container">
                <label for="cidade">Filtrar por Cidade:</label>
                <select id="cidade" onchange="filterByCity()">
                    <option value="">Todas</option>
                    <?php 
                    $selectedCity = isset($_GET['cidade']) ? $_GET['cidade'] : '';
                    foreach ($cidades as $cidade) : 
                        if (!empty($cidade)) { // Remover campo vazio
                    ?>
                        <option value="<?php echo $cidade; ?>" <?php echo ($cidade == $selectedCity) ? 'selected' : ''; ?>>
                            <?php echo $cidade; ?>
                        </option>
                    <?php 
                        }
                    endforeach; 
                    ?>
                </select>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>ID Serviço</th>
                        <th>Nome da Oficina</th>
                        <th>Nome do Serviço</th>
                        <th>Descrição do Serviço</th>
                        <th>Cidade</th>
                        <th>Preço (R$)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $selectedCity = isset($_GET['cidade']) ? $_GET['cidade'] : '';

                    $query = "SELECT DISTINCT servico.idServico,
                                         COALESCE(pessoajuridica.nomeSocial, prestadordeservico.nomeCompleto) AS nomeSocial,
                                         servico.nome AS nomeServico,
                                         servico.descricao,
                                         endereco.cidade,
                                         oficinaservicos.preco
                              FROM servico
                              LEFT JOIN pessoajuridica ON servico.id_Usuario = pessoajuridica.id_Usuario
                              LEFT JOIN prestadordeservico ON servico.id_Usuario = prestadordeservico.id_Usuario
                              JOIN endereco ON endereco.id_Usuario = servico.id_Usuario
                              LEFT JOIN oficinaservicos ON servico.idServico = oficinaservicos.idServico
                              WHERE endereco.cidade IS NOT NULL";

                    if ($selectedCity) {
                        $query .= " AND endereco.cidade = ?";
                    }

                    $stmt = $conn->prepare($query);

                    if ($selectedCity) {
                        $stmt->bind_param("s", $selectedCity);
                    }

                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $servicosExibidos = [];
                        while ($row = $result->fetch_assoc()) {
                            if (!in_array($row['idServico'], $servicosExibidos)) {
                                echo "<tr>";
                                echo "<td>" . $row['idServico'] . "</td>";
                                echo "<td>" . $row['nomeSocial'] . "</td>";
                                echo "<td>" . $row['nomeServico'] . "</td>";
                                echo "<td>" . $row['descricao'] . "</td>";
                                echo "<td>" . $row['cidade'] . "</td>";
                                echo "<td>" . $row['preco'] . "</td>";
                                echo "</tr>";
                                $servicosExibidos[] = $row['idServico'];
                            }
                        }
                    } else {
                        echo "<tr><td colspan='6'>Nenhum serviço encontrado</td></tr>";
                    }

                    $stmt->close();
                    ?>
                </tbody>
            </table>

            <div class="valor-total">
                Valor Total do Orçamento: R$ <span id="valorTotal"><?php echo number_format($valorTotalOrcamento, 2, ',', '.'); ?></span>
            </div>
        </div>
    </div>

    <div class="back-button">
        <a href="../../login/admFisica.php">Voltar</a>
    </div>

    <script>
        function filterByCity() {
            var city = document.getElementById('cidade').value;
            window.location.href = '?cidade=' + city;
        }
    </script>
</body>
</html>