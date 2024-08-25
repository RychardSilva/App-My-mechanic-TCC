<?php
require("../connect/connect.php");

$registerusername = isset($_POST['registerusername']) ? $_POST['registerusername'] : null;
$registertelephone = isset($_POST['registertelephone']) ? $_POST['registertelephone'] : null;
$registeremail = isset($_POST['registeremail']) ? $_POST['registeremail'] : null;
$registersenha = isset($_POST['registersenha']) ? $_POST['registersenha'] : null;
$tipoUsuario = isset($_POST['tipoUsuario']) ? $_POST['tipoUsuario'] : null;
$numeroendereco = isset($_POST['numeroendereco']) ? $_POST['numeroendereco'] : null;
$complementoendereco = isset($_POST['complementoendereco']) ? $_POST['complementoendereco'] : null;

// Aplicar hash MD5 à senha
$registersenha = md5($registersenha);


if (!$registersenha) {
    die("Erro: Senha não foi enviada corretamente.");
}


if ($registerusername && $registertelephone && $registeremail && $registersenha && $tipoUsuario && $numeroendereco) {
    $stmt = $conn->prepare("INSERT INTO Usuario (nomeUsuario, telefone, tipoUsuario, email, senha, numeroEnd, complementoEnd) VALUES (?,?,?,?,?,?,?)");
    $stmt->bind_param("sssssis", $registerusername, $registertelephone, $tipoUsuario, $registeremail, $registersenha, $numeroendereco, $complementoendereco);

    if ($stmt->execute()) {
        $stmt->close();
        $userId = $conn->insert_id; // Obtém o ID do usuário recém-inserido
        echo $tipoUsuario;

        if ($tipoUsuario == 'PessoaFisica') {
            $cpf = isset($_POST['cpf']) ? $_POST['cpf'] : null;
            $nomeCompleto = isset($_POST['nomeCompleto']) ? $_POST['nomeCompleto'] : null;
            $dataNasc = isset($_POST['dataNasc']) ? $_POST['dataNasc'] : null;

            echo "CPF: $cpf, Nome: $nomeCompleto, Data de Nascimento: $dataNasc"; // Para depuração

            if ($cpf && $nomeCompleto && $dataNasc) {
                $stmt = $conn->prepare("INSERT INTO pessoafisica (id_Usuario, cpf, nomeCompleto, dataNasc) VALUES (?,?,?,?)");
                $stmt->bind_param("isss", $userId, $cpf, $nomeCompleto, $dataNasc);

                if ($stmt->execute()) {
                    $stmt->close();
                    $conn->close();
                    echo "<script language='javascript' type='text/javascript'>
                    alert('Usuário cadastrado com sucesso!');window.location.href='../login/login.html';</script>";
                } else {
                    echo "Erro ao inserir dados específicos: " . $stmt->error;
                }
            } else {
                echo "Erro: Todos os campos de Pessoa Física são obrigatórios.";
            }
        }elseif ($tipoUsuario == 'PessoaJuridica') {
            $cnpj = isset($_POST['cnpj']) ? $_POST['cnpj'] : null;
            $razaoSocial = isset($_POST['razaoSocial']) ? $_POST['razaoSocial'] : null;
            $nomeSocial = isset($_POST['nomeSocial']) ? $_POST['nomeSocial'] : null;

            if ($cnpj && $razaoSocial && $nomeSocial) {
                $stmt = $conn->prepare("INSERT INTO pessoajuridica (id_Usuario, cnpj, razaoSocial, nomeSocial) VALUES (?,?,?,?)");
                $stmt->bind_param("isss", $userId, $cnpj, $razaoSocial, $nomeSocial);

                if ($stmt->execute()) {
                    $stmt->close();
                    $conn->close();
                    echo "<script language='javascript' type='text/javascript'>
                    alert('Usuário cadastrado com sucesso!');window.location.href='../login/login.html';</script>";
                } else {
                    echo "Erro ao inserir dados específicos: " . $stmt->error;
                }
            } else {
                echo "Erro: Todos os campos de Pessoa Jurídica são obrigatórios.";
            }
        }elseif ($tipoUsuario == 'PrestadorDeServico') {
            $stmt = $conn->prepare("INSERT INTO prestadordeservico (id_Usuario) VALUES (?)");
            $stmt->bind_param("i", $userId);
               
            if ($stmt->execute()) {
                $stmt->close();
                $conn->close();
                echo "<script language='javascript' type='text/javascript'>
                alert('Usuário cadastrado com sucesso!');window.location.href='../login/login.html';</script>";
            } else {
                echo "Erro ao inserir dados específicos: " . $stmt->error;
            }
        }
    } else {
        echo "Erro ao inserir dados do usuário: " . $stmt->error;
    }
} else {
    echo "Erro: Todos os campos são obrigatórios.";
}
