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
            flex-direction: column;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 800px;
            text-align: center;
        }
        h1 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
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
        .btn-secondary {
            display: inline-block;
            padding: 10px 20px;
            background-color: #6c757d;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
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
            th, td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Lista de Veículos de <?php echo ($_SESSION["username"]); ?></h1>
        <table>
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
        <a href="../../login/admFisica.php" class="btn btn-secondary">Voltar</a>
    </div>
</body>
</html>

<?php
// Fechar a conexão
$conn->close();
?>