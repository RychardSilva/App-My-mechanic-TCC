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
$query = "SELECT oficina.nome, oficina.horarioFuncionamento_inic, oficina.horarioFuncionamento_fin, oficina.telefone, endereco.cidade 
    FROM oficina
    JOIN endereco ON oficina.id_Usuario = endereco.id_Usuario";

// Adicionar filtro por cidade, se fornecido
if (!empty($cidadeFiltro)) {
    $query .= " WHERE endereco.cidade = ?";
}

$stmt = $conn->prepare($query);

if (!empty($cidadeFiltro)) {
    $stmt->bind_param("s", $cidadeFiltro); // Vincular a cidade ao parâmetro da consulta
}

if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    echo "Erro ao preparar a consulta: " . $conn->error;
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Oficinas</title>
</head>
<body>
    <h1>Lista de Oficinas</h1>

    <!-- Formulário para filtrar por cidade -->
    <form method="GET" action="">
        <label for="cidade">Filtrar por Cidade:</label>
        <input type="text" id="cidade" name="cidade" value="<?php echo htmlspecialchars($cidadeFiltro); ?>">
        <button type="submit">Filtrar</button>
    </form>

    <table border="1" style="margin-top: 20px;">
        <tr>
            <th>Nome da Oficina</th>
            <th>Horário de Funcionamento (Início)</th>
            <th>Horário de Funcionamento (Fim)</th>
            <th>Telefone</th>
            <th>Cidade</th>
        </tr>
        <?php
        // Verificar se a consulta retornou resultados
        if ($result->num_rows > 0) {
            // Exibir os dados de cada linha
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["nome"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["horarioFuncionamento_inic"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["horarioFuncionamento_fin"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["telefone"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["cidade"]) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>Nenhuma oficina encontrada</td></tr>";
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
