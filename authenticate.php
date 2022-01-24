<?php
session_start();

if ( !isset($_POST['username'], $_POST['password']) ) {
	// echo('Insere todos os dados!');
    $_SESSION['error'] = 'Insere todos os dados!';
    // header("Refresh: 5; url=login.php");
    header("Location: login.php");
    exit;
}

require_once('db.php');

if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
	// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	$stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password_hash);
        $stmt->fetch();
        $password = $_POST['password'];
        if (password_verify($password, $password_hash)) {
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $_POST['username'];
            $_SESSION['id'] = $id;
            header('Location: /');
        } else {
            $_SESSION['error'] = 'Palavra-passe incorreta!';
            header('Location: login.php');
            echo 'A Palavra-passe está incorreta!';
        }
    } else {
        $_SESSION['error'] = 'Nome de utilizador incorreto!';
        header('Location: login.php');
        echo 'O Nome de Utilizador não existe!';
    }

	$stmt->close();
}
?>