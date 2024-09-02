<?php
session_start();

// Verificar se a sessão está iniciada corretamente
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.html");
    exit;
}

require("../../connect/connect.php");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnóstico de Problemas no Carro</title>
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
        form select {
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
        #resultado {
            margin: 20px auto;
            width: 80%;
            max-width: 600px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .btn-voltar {
            display: block;
            width: 80%;
            max-width: 600px;
            margin: 20px auto;
            text-align: center;
        }
        .btn-voltar a {
            background-color: #808080;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
        }
        .btn-voltar a:hover {
            background-color: #696969;
        }
    </style>
</head>
<body>
    <h1>Formulário de Diagnóstico de Problemas no Carro</h1>
    <form id="diagnosticoForm">
        <label for="problema">Selecione o problema:</label>
        <select id="problema" name="problema" required>
            <option value="">Selecione...</option>
            <option value="motor_nao_liga">Motor não liga</option>
            <option value="barulho_estranho">Barulho estranho</option>
            <option value="luz_vermelha_painel">Luz vermelha no painel</option>
            <option value="freios_ruins">Freios não funcionam bem</option>
            <option value="superaquecimento">Superaquecimento</option>
            <option value="vazamento_oleo">Vazamento de óleo</option>
            <option value="pneu_furado">Pneu furado</option>
            <option value="direcao_dura">Direção dura</option>
            <option value="bateria_fraca">Bateria fraca</option>
            <option value="fumaça_excesso">Fumaça em excesso</option>
            <option value="consumo_combustivel">Consumo excessivo de combustível</option>
            <option value="problema_transmissao">Problema na transmissão</option>
            <option value="problema_suspensao">Problema na suspensão</option>
            <option value="problema_ar_condicionado">Problema no ar condicionado</option>
            <option value="problema_freio_mao">Problema no freio de mão</option>
            <option value="nao_descoberto">Não foi descoberto o meu problema</option>
        </select>

        <button type="submit">Enviar</button>
    </form>

    <div id="resultado"></div>

    <div class="btn-voltar">
        <a href="../../login/admFisica.php">Voltar</a>
    </div>

    <script>
        document.getElementById('diagnosticoForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const problema = document.getElementById('problema').value;
            const resultadoDiv = document.getElementById('resultado');

            let diagnostico = '';

            switch (problema) {
                case 'motor_nao_liga':
                    diagnostico = 'Possível problema: Bateria descarregada ou problema no motor de arranque.';
                    break;
                case 'barulho_estranho':
                    diagnostico = 'Possível problema: Problema no escapamento ou nos rolamentos.';
                    break;
                case 'luz_vermelha_painel':
                    diagnostico = 'Possível problema: Problema no sistema elétrico ou no motor.';
                    break;
                case 'freios_ruins':
                    diagnostico = 'Possível problema: Desgaste nas pastilhas de freio ou problema no sistema hidráulico.';
                    break;
                case 'superaquecimento':
                    diagnostico = 'Possível problema: Falta de líquido de arrefecimento ou problema no radiador.';
                    break;
                case 'vazamento_oleo':
                    diagnostico = 'Possível problema: Vazamento de óleo no motor ou na transmissão.';
                    break;
                case 'pneu_furado':
                    diagnostico = 'Possível problema: Pneu furado ou danificado.';
                    break;
                case 'direcao_dura':
                    diagnostico = 'Possível problema: Problema na direção hidráulica ou falta de fluido.';
                    break;
                case 'bateria_fraca':
                    diagnostico = 'Possível problema: Bateria fraca ou com defeito.';
                    break;
                case 'fumaça_excesso':
                    diagnostico = 'Possível problema: Problema no motor ou no sistema de exaustão.';
                    break;
                case 'consumo_combustivel':
                    diagnostico = 'Possível problema: Problema no sistema de injeção ou no filtro de ar.';
                    break;
                case 'problema_transmissao':
                    diagnostico = 'Possível problema: Problema na transmissão ou no câmbio.';
                    break;
                case 'problema_suspensao':
                    diagnostico = 'Possível problema: Problema nos amortecedores ou nas molas.';
                    break;
                case 'problema_ar_condicionado':
                    diagnostico = 'Possível problema: Falta de gás refrigerante ou problema no compressor.';
                    break;
                case 'problema_freio_mao':
                    diagnostico = 'Possível problema: Problema no cabo do freio de mão ou nas pastilhas.';
                    break;
                case 'nao_descoberto':
                    diagnostico = 'Problema não identificado. Recomendamos solicitar um prestador de serviço para diagnosticar seu carro.';
                    resultadoDiv.innerHTML = `<p>${diagnostico}</p>`;
                    resultadoDiv.innerHTML += `<p>Clique no botão voltar abaixo para procurar um prestador de serviço para diagnosticar o problema do seu veículo.</p>`;
                    return;
            }

            resultadoDiv.innerHTML = `<p>${diagnostico}</p>`;
        });
    </script>
</body>
</html>
