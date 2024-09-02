<?php
session_start();

// Verificar se a sessão está iniciada corretamente
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.html");
    exit;
}

require("../../connect/connect.php");

// Inicializar as variáveis de palavra-chave, cidade e formato
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$cidade = isset($_GET['cidade']) ? $_GET['cidade'] : '';
$format = isset($_GET['format']) ? $_GET['format'] : 'html'; // Default para HTML

// Preparar a consulta com busca por palavra-chave e cidade
$query = "SELECT servico.idServico,
                 COALESCE(pessoajuridica.nomeSocial, prestadordeservico.nomeCompleto) AS nome,
                 servico.nome AS nomeServico,
                 servico.descricao,
                 TRIM(BOTH ', ' FROM GROUP_CONCAT(DISTINCT endereco.cidade ORDER BY endereco.cidade ASC SEPARATOR ', ')) AS cidades
          FROM servico
          LEFT JOIN pessoajuridica ON servico.id_Usuario = pessoajuridica.id_Usuario
          LEFT JOIN prestadordeservico ON servico.id_Usuario = prestadordeservico.id_Usuario
          LEFT JOIN endereco ON endereco.id_Usuario = servico.id_Usuario
          WHERE (servico.nome LIKE ? OR servico.descricao LIKE ?)";

// Adicionar filtro por cidade se selecionado
if (!empty($cidade)) {
    $query .= " AND endereco.cidade = ?";
}

// Adicionar cláusula GROUP BY
$query .= " GROUP BY servico.idServico";

// Preparar e executar a consulta
$stmt = $conn->prepare($query);
$searchKeyword = "%" . $keyword . "%";

// Verificar se a cidade foi selecionada
if (!empty($cidade)) {
    $stmt->bind_param("sss", $searchKeyword, $searchKeyword, $cidade);
} else {
    $stmt->bind_param("ss", $searchKeyword, $searchKeyword);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Serviços</title>
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
    <h1>Lista de Serviços</h1>

    <!-- Formulário de Busca -->
    <form method="GET" action="">
        <label for="keyword">Buscar por palavra-chave:</label>
        <input type="text" id="keyword" name="keyword" value="<?php echo htmlspecialchars($keyword); ?>">

        <label for="cidade">Filtrar por cidade:</label>
        <input type="text" id="cidade" name="cidade" value="<?php echo htmlspecialchars($cidade); ?>">

        <button type="submit">Buscar</button>
    </form>

    <table>
        <tr>
            <th>ID Serviço</th>
            <th>Nome da Oficina ou Prestador</th>
            <th>Nome do Serviço</th>
            <th>Descrição detalhada do serviço</th>
            <th>Cidades</th>
        </tr>
        <?php
        // Verificar se a consulta retornou resultados
        if ($result->num_rows > 0) {
            // Exibir os dados de cada linha
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["idServico"] . "</td>";
                echo "<td>" . $row["nome"] . "</td>";
                echo "<td>" . $row["nomeServico"] . "</td>";
                echo "<td>" . $row["descricao"] . "</td>";
                echo "<td>" . $row["cidades"] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>Nenhum serviço encontrado</td></tr>";
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