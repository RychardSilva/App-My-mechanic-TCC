<?php
session_start();

// Verificar se a sessão está iniciada corretamente
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.html");
    exit;
}

require("../../connect/connect.php");

// Obter a cidade a partir do parâmetro GET
$cidadeFiltro = isset($_GET['cidade']) ? $_GET['cidade'] : '';

// Preparar a consulta para buscar informações da tabela oficina
$queryOficinas = "SELECT oficina.nome AS nome,
                         oficina.horarioFuncionamento_inic,
                         oficina.horarioFuncionamento_fin,
                         oficina.telefone,
                         endereco.cidade
                  FROM oficina
                  JOIN endereco ON oficina.id_Usuario = endereco.id_Usuario
                  WHERE endereco.cidade IS NOT NULL AND endereco.cidade != ''";

// Adicionar filtro por cidade, se fornecido
if (!empty($cidadeFiltro)) {
    $queryOficinas .= " AND endereco.cidade = ?";
}

// Preparar a consulta para buscar informações da tabela prestador de serviço
$queryPrestadores = "SELECT prestadordeservico.nomeCompleto AS nome,
                            NULL AS horarioFuncionamento_inic,
                            NULL AS horarioFuncionamento_fin,
                            usuario.telefone,
                            endereco.cidade
                     FROM prestadordeservico
                     JOIN usuario ON prestadordeservico.id_Usuario = usuario.idUsuario
                     JOIN endereco ON usuario.idUsuario = endereco.id_Usuario
                     WHERE endereco.cidade IS NOT NULL AND endereco.cidade != ''";

// Adicionar filtro por cidade, se fornecido
if (!empty($cidadeFiltro)) {
    $queryPrestadores .= " AND endereco.cidade = ?";
}

// Executar a consulta para oficinas
$stmtOficinas = $conn->prepare($queryOficinas);
if (!empty($cidadeFiltro)) {
    $stmtOficinas->bind_param("s", $cidadeFiltro);
}
$stmtOficinas->execute();
$resultOficinas = $stmtOficinas->get_result();

// Executar a consulta para prestadores de serviço
$stmtPrestadores = $conn->prepare($queryPrestadores);
if (!empty($cidadeFiltro)) {
    $stmtPrestadores->bind_param("s", $cidadeFiltro);
}
$stmtPrestadores->execute();
$resultPrestadores = $stmtPrestadores->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Oficinas e Prestadores de Serviço</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        h1 {
            background-color: #ffffff;
            color: black;
            padding: 10px 0;
            text-align: center;
            border-bottom: 1px solid #ccc;
        }
        form {
            margin: 20px auto;
            width: 80%;
            max-width: 600px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        form label {
            display: block;
            margin-bottom: 8px;
        }
        form input {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        form button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        form button:hover {
            background-color: #45a049;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #ffffff;
            color: black;
            border-bottom: 2px solid #ccc;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 20px auto;
            text-align: center;
            background-color: #808080;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .btn:hover {
            background-color: #696969;
        }
    </style>
</head>
<body>
    <h1>Lista de Oficinas e Prestadores de Serviço</h1>

    <!-- Formulário para filtrar por cidade -->
    <form method="GET" action="">
        <label for="cidade">Filtrar por Cidade:</label>
        <input type="text" id="cidade" name="cidade" value="<?php echo htmlspecialchars($cidadeFiltro); ?>">
        <button type="submit">Filtrar</button>
    </form>

    <table>
        <tr>
            <th>Nome da Oficina ou Prestador</th>
            <th>Horário de Funcionamento (Início)</th>
            <th>Horário de Funcionamento (Fim)</th>
            <th>Telefone</th>
            <th>Cidade</th>
        </tr>
        <?php
        // Verificar se a consulta retornou resultados para oficinas
        if ($resultOficinas->num_rows > 0) {
            // Exibir os dados de cada linha
            while ($row = $resultOficinas->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["nome"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["horarioFuncionamento_inic"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["horarioFuncionamento_fin"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["telefone"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["cidade"]) . "</td>";
                echo "</tr>";
            }
        }

        // Verificar se a consulta retornou resultados para prestadores de serviço
        if ($resultPrestadores->num_rows > 0) {
            // Exibir os dados de cada linha
            while ($row = $resultPrestadores->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["nome"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["horarioFuncionamento_inic"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["horarioFuncionamento_fin"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["telefone"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["cidade"]) . "</td>";
                echo "</tr>";
            }
        }

        // Caso não haja resultados
        if ($resultOficinas->num_rows == 0 && $resultPrestadores->num_rows == 0) {
            echo "<tr><td colspan='5'>Nenhuma oficina ou prestador de serviço encontrado</td></tr>";
        }
        ?>
    </table>

    <!-- Botão Voltar -->
    <div style="text-align: center;">
        <a href="../../login/admFisica.php" class="btn">Voltar</a>
    </div>
</body>
</html>

<?php
// Fechar a conexão
$conn->close();
?>