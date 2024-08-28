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
$query = "SELECT servico.idServico, pessoajuridica.nomeSocial, servico.nome, servico.descricao, endereco.cidade
          FROM servico 
          JOIN pessoajuridica ON servico.id_Usuario = pessoajuridica.id_Usuario
          JOIN endereco ON endereco.id_Usuario = servico.id_Usuario
          WHERE (servico.nome LIKE ? OR servico.descricao LIKE ?)";

// Adicionar filtro por cidade se selecionado
if (!empty($cidade)) {
    $query .= " AND endereco.cidade = ?";
}

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

// Se o formato solicitado for JSON, retornar os dados em JSON
if ($format === 'json') {
    $services = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $services[] = $row;
        }
    }
    header('Content-Type: application/json');
    echo json_encode($services);
    //$conn->close();
   // exit; // Terminar a execução para evitar a renderização HTML
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Serviços</title>
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

    <table border="1" style="margin-top: 20px;">
        <tr>
            <th>ID Serviço</th>
            <th>Nome da Oficina ou Prestador</th>
            <th>Nome do Serviço</th>
            <th>Descrição detalhada do serviço</th>
            <th>Cidade</th>
        </tr>
        <?php

        
        // Verificar se a consulta retornou resultados
        if ($result->num_rows > 0) {
            // Exibir os dados de cada linha
            while ($row = $result->fetch_assoc()) {
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
    </table>

    <!-- Botão Voltar -->
    <div style="margin-top: 20px;">
        <a href="../../login/admFisica.php" class="btn btn-secondary">Voltar</a>
    </div>
</body>
</html>

<?php
// Fechar a conexão
$conn->close();
?>
