<?php
require("../connect/connect.php");

$registerusername = $_POST['registerusername'];
$registertelephone = $_POST['registertelephone'];
$registeremail = $_POST['registeremail'];
$registrasenha = $_POST['registersenha'];
$cpf = $_POST['cpf'];
$nomeCompleto = $_POST['nomeCompleto'];
$dataNasc = $_POST['dataNasc'];
$cnpj = $_POST['cnpj'];
$razaoSocial = $_POST['razaoSocial'];
$nomeSocial = $_POST['nomeSocial'];
$numeroendereco = $_POST['numeroendereco'];
$complementoendereco = $_POST['complementoendereco'];

$stmt = $conn->prepare("INSERT INTO usuario (nome, telefone, email, senha, numeroendereco, complementoendereco) VALUES (?,?,?,?,?,?)");
$stmt->bind_param("ssssis", $registerusername, $registertelephone, $registeremail, $registrasenha, $numeroendereco, $complementoendereco);

if($stmt->execute()){
    $stmt->close();
    $conn->close();
    echo "<script language='javascript' type='text/javascript'>
    alert('Usuário cadastrado com sucesso!');window.location.href='login.html';</script>";
}

?>