<?php
session_start();

// Verificar se a sessão está iniciada corretamente
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.html");
    exit;
}

require("../../connect/connect.php");

// Obter o ID do usuário logado
$idUsuario = $_SESSION["idUsuario"];

// Atualizar os dados no banco de dados
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $idServico = $_POST['idServico'];
    $nomeServico = $_POST['nomeServico'];
    $descricao = $_POST['descricao'];

    $stmt = $conn->prepare("UPDATE servico SET nome = ?, descricao = ? WHERE idServico = ? AND id_Usuario = ?");
    $stmt->bind_param("ssii", $nomeServico, $descricao, $idServico, $idUsuario);

    if ($stmt->execute()) {
        echo "<script>alert('Serviço atualizado com sucesso!');</script>";
    } else {
        echo "<script>alert('Erro ao atualizar serviço: " . $stmt->error . "');</script>";
    }
}

// Excluir o serviço do banco de dados
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $idServico = $_POST['idServico'];

    $stmt = $conn->prepare("DELETE FROM servico WHERE idServico = ? AND id_Usuario = ?");
    $stmt->bind_param("ii", $idServico, $idUsuario);

    if ($stmt->execute()) {
        echo "<script>alert('Serviço excluído com sucesso!');</script>";
    } else {
        echo "<script>alert('Erro ao excluir serviço: " . $stmt->error . "');</script>";
    }
}

// Preparar e executar a consulta
$stmt = $conn->prepare("
    SELECT s.idServico, s.nome, s.descricao
    FROM servico s
    WHERE s.id_Usuario = ?
");
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Serviços</title>
    <link rel="stylesheet" href="./prestador.css">
</head>
<body>
    <h1> Gerenciar meus Serviços</h1>
    <table>
        <thead>
            <tr>
                <th>ID Serviço</th>
                <th>Nome Serviço</th>
                <th>Descrição</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["idServico"] . "</td>";
                    echo "<td>" . $row["nome"] . "</td>";
                    echo "<td>" . $row["descricao"] . "</td>";
                    echo "<td>
                            <button class='btn-edit' data-id='" . $row["idServico"] . "' data-nome='" . $row["nome"] . "' data-descricao='" . $row["descricao"] . "'>Editar</button>
                            <button class='btn-delete' data-id='" . $row["idServico"] . "'>Excluir</button>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Nenhum serviço encontrado</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Formulário de Edição -->
    <div id="edit-form-container" style="display: none;">
        <form id="edit-form" method="post">
            <input type="hidden" id="edit-idServico" name="idServico">
            <div>
                <label for="edit-nomeServico">Nome do serviço:</label>
                <input type="text" id="edit-nomeServico" name="nomeServico" required>
            </div>
            <div>
                <label for="edit-descricao">Descrição do serviço:</label>
                <input type="text" id="edit-descricao" name="descricao" required>
            </div>
            <div class="button-group">
                <button type="submit" name="update">Salvar</button>
                <button type="button" id="cancel-edit">Cancelar</button>
            </div>
        </form>
    </div>

    <!-- Formulário de Exclusão -->
    <form id="delete-form" method="post" style="display: none;">
        <input type="hidden" id="delete-idServico" name="idServico">
        <input type="hidden" name="delete" value="true">
    </form>

    <!-- Botão Voltar -->
    <div class="button-container">
        <a href="../../login/admPrestador.php" class="btn-secondary">Voltar</a>
    </div>
    <script src="editardados.js"></script>
</body>
</html>

<?php
$conn->close();
?>  