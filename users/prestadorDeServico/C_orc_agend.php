<?php
session_start();

// Verificar se a sessão está iniciada corretamente
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.html");
    exit;
}

require("../../connect/connect.php");

// Assume que o ID do usuário está armazenado na sessão
$idUsuario = $_SESSION['idUsuario'];

// Consulta SQL para obter os dados de "Meus Agendamentos"
$sql_agendamentos = "SELECT
                        a.id_AgendamentoOficina AS numero_orcamento,
                        v.placa AS placa_veiculo,
                        a.dataAgen AS data_agendamento,
                        a.hora AS horario,
                        o.nome AS nome_oficina
                    FROM
                        agendamento a
                    JOIN
                        veiculo v ON a.id_Veiculo = v.idVeiculo
                    JOIN
                        agendamentoservicooficina aso ON a.id_AgendamentoOficina = aso.idAgendamentoOficina
                    JOIN
                        oficina o ON aso.id_Oficina = o.idOficina
                    WHERE
                        a.id_Usuario = ?"; // Filtra pelo usuário logado (pessoaFisica)

// Consulta SQL para obter os dados de "Meus Orçamentos"
$sql_orcamentos = "SELECT
                        o.idOrcamento AS numero_orcamento,
                        o.dataOrcamento AS data_orcamento,
                        o.validadeOrcamento AS validade_orcamento,
                        of.idOficina AS id_oficina,
                        of.nome AS nome_oficina,
                        s.descricao AS descricao_servico,
                        MAX(os.valorTotal) AS valor_total
                    FROM
                        orcamento o
                    INNER JOIN
                        orcamentooficinaservico os ON o.idOrcamento = os.id_Orcamento
                    INNER JOIN
                        oficinaservicos ofs ON os.id_OficinaServico = ofs.idOficinaServ
                    INNER JOIN
                        oficina of ON ofs.id_Oficina = of.idOficina
                    INNER JOIN
                        servico s ON ofs.idServico = s.idServico
                    WHERE
                        o.id_Usuario = ? -- Filtra pelo usuário logado (pessoaFisica)
                    GROUP BY
                        o.idOrcamento, o.dataOrcamento, o.validadeOrcamento, of.idOficina, of.nome, s.descricao
                    ORDER BY
                        o.idOrcamento DESC";

if ($stmt_agendamentos = $conn->prepare($sql_agendamentos)) {
    $stmt_agendamentos->bind_param("i", $idUsuario);
    $stmt_agendamentos->execute();
    $result_agendamentos = $stmt_agendamentos->get_result();
} else {
    echo "Erro na preparação da consulta de agendamentos: " . $conn->error;
}

if ($stmt_orcamentos = $conn->prepare($sql_orcamentos)) {
    $stmt_orcamentos->bind_param("i", $idUsuario);
    $stmt_orcamentos->execute();
    $result_orcamentos = $stmt_orcamentos->get_result();
} else {
    echo "Erro na preparação da consulta de orçamentos: " . $conn->error;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Agendamentos e Orçamentos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        h2 {
            background-color: #ffffff;
            color: black;
            padding: 10px 0;
            text-align: center;
            border-bottom: 1px solid #ccc;
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
        .button-container {
            text-align: center;
            margin-top: 20px;
        }
        .button-container button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            background-color: #808080; /* Cor cinza */
            color: white; /* Cor do texto */
            border: none; /* Remover borda */
            border-radius: 5px; /* Bordas arredondadas */
        }
        .button-container button:hover {
            background-color: #696969; /* Cor cinza mais escura ao passar o mouse */
        }
    </style>
</head>
<body>

<h2>Meus Agendamentos</h2>

<table>
    <thead>
        <tr>
            <th>Número Orçamento</th>
            <th>Placa do Veículo</th>
            <th>Data Agendamento</th>
            <th>Horário</th>
            <th>Nome da Oficina/Prestador</th>
        </tr>
    </thead>
    <tbody>
        <?php
            if ($result_agendamentos->num_rows > 0) {
                while ($row = $result_agendamentos->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['numero_orcamento']}</td>
                            <td>{$row['placa_veiculo']}</td>
                            <td>{$row['data_agendamento']}</td>
                            <td>{$row['horario']}</td>
                            <td>{$row['nome_oficina']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Nenhum agendamento encontrado.</td></tr>";
            }

            $stmt_agendamentos->close();
        ?>
    </tbody>
</table>

<h2>Meus Orçamentos</h2>

<table>
    <thead>
        <tr>
            <th>Número Orçamento</th>
            <th>Data do Orçamento</th>
            <th>Validade do Orçamento</th>
            <th>Nome da Oficina</th>
            <th>Descrição do Serviço</th>
            <th>Valor Total</th>
        </tr>
    </thead>
    <tbody>
        <?php
            if ($result_orcamentos->num_rows > 0) {
                while ($row = $result_orcamentos->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['numero_orcamento']}</td>
                            <td>{$row['data_orcamento']}</td>
                            <td>{$row['validade_orcamento']}</td>
                            <td>{$row['nome_oficina']}</td>
                            <td>{$row['descricao_servico']}</td>
                            <td>R$ {$row['valor_total']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>Nenhum orçamento encontrado.</td></tr>";
            }

            $stmt_orcamentos->close();
            $conn->close();
        ?>
    </tbody>
</table>
<div class="button-container">
    <button onclick="window.location.href='../../login/admPrestador.php'">Voltar</button>
</div>

</body>
</html>