<?php

session_start();

// Verificar se a sessão está iniciada corretamente
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.html");
    exit;
}

require("../../connect/connect.php");
// Preparar e executar a consulta
$stmt = $conn->prepare("SELECT *FROM veiculo");
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Veículos</title>
</head>
<body>
    <h1>Lista de Veículos de <?php echo ($_SESSION["username"]); ?> </h1>
    <table border="1">
        <tr>
            <!-- Substitua os nomes das colunas conforme necessário -->
                       
            <th>Modelo</th>
            <th>Ano</th>
            <th>Cor</th>
            <th>Placa</th>
            <!-- Acrescente mais colunas conforme a estrutura da tabela -->
        </tr>
        <?php
        // Verificar se a consulta retornou resultados
        if ($result->num_rows > 0) {
            // Exibir os dados de cada linha
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                
               
                echo "<td>" . $row["modelo"] . "</td>";
                echo "<td>" . $row["ano"] . "</td>";                
                echo "<td>" . $row["placa"] . "</td>";
                echo "<td>" . $row["cor"] . "</td>";
                // Adicione mais células conforme as colunas da tabela
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>Nenhum veículo encontrado</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
// Fechar a conexão
$conn->close();
?>