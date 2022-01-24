<?php
session_start();

include_once('db.php');

if (!isset($_POST['username'], $_POST['password'], $_POST['email'])) {
	echo('Insere todos os dados!');
	header("Refresh: 5; url=register.php");
	exit;	
}
if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
	echo('Insere todos os dados!');
	header("Refresh: 5; url=register.php");
	exit;
}
if ($stmt = $con->prepare('SELECT 1 FROM accounts WHERE username = ?')) {
	// Bind parameters (s = string, i = int, b = blob, etc), hash the password using the PHP password_hash function.
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	$stmt->store_result();
	if ($stmt->num_rows > 0) {
		$_SESSION['error'] = 'Nome de utilizador já existe!';
		header('Location: register.php');
	}
	else if ($stmt = $con->prepare("SELECT 1 FROM accounts WHERE email = ?")) {
		$stmt->bind_param('s', $_POST['email']);
		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$_SESSION['error'] = 'Email já existe!';
			header('Location: register.php');
		} else {
			$username = $_POST['username'];
			$password = $_POST['password'];
			$email = $_POST['email'];
			$password_hash = password_hash($password, PASSWORD_DEFAULT);
			$sql = "INSERT INTO `accounts` (`username`, `password`, `email`) VALUES (?, ?, ?)";
			$stmt2 = $con->prepare($sql);
			$stmt2->bind_param('sss', $username, $password_hash, $email);
			$stmt2->execute();
			$stmt2->close();
			$_SESSION['success'] = 'Registo efetuado com sucesso!';
			header('Location: login.php');
		}
	}
} else {
	echo 'SQL ERROR!';
}
$stmt->close();
$con->close();
?>