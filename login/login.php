<?php
session_start();

$login = $_POST["Usuario"];
$entrar = $_POST["entrar"];
$senha = md5($_POST["senha"]);
$conn = new mysqli("localhost","root","","my_mechanic",3306);
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
if (isset($entrar)) {
	$query_select = "SELECT idUsuario, tipoUsuario, nomeUsuario FROM usuario WHERE nomeUsuario = '$login' and senha = '$senha'" ;
	$result = $conn->query($query_select);
	$conn->close();
	if($result->num_rows <= 0){
		echo"<script language='javascript' type='text/javascript'>
		alert('Login e/ou senha incorretos');window.location.href='login.html';</script>";
		die();
        
    }else{
		$_SESSION["login"] = $login;
		$_SESSION["loggedin"] = true;
		$_SESSION["username"] = $login;
		$row = $result->fetch_assoc();
		$_SESSION['usuario'] = $row;
		$_SESSION["idUsuario"] = $row["idUsuario"];
        if($row["tipoUsuario"] == "prestadorDeServico")
			header("Location:admPrestador.php");	
		elseif($row["tipoUsuario"] == "pessoaJuridica")
			header("Location:admJuridica.php");
		else
			header("Location:admFisica.php");
	}
}
?>
