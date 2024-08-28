<?php

session_start();

// Verificar se a sessão está iniciada corretamente
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.html");
    exit;
}

require("../../connect/connect.php");
// Preparar e executar a consulta
$stmt = $conn->prepare("SELECT * FROM veiculo WHERE id_Usuario = ?");
$stmt->bind_param("i", $_SESSION["idUsuario"]);
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
            <th>Modelo</th>
            <th>Ano</th>
            <th>Placa</th>
            <th>Cor</th>
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
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Nenhum veículo encontrado</td></tr>";
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
