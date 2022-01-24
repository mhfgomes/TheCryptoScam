<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
	header('Location: /');
	exit;
}

if (empty($_POST['changetype'])) {
    $_SESSION['error'] = 'Não foi selecionado nenhum tipo de alteração!';
    header('Location: profile.php');
    exit;
}

require_once('db.php');

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if($_POST['changetype'] == 'username') {
        if($stmt = $con->prepare('SELECT 1 FROM accounts WHERE username = ?'))
        $stmt->bind_param('s', $_POST['username']);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows > 0) {
            $_SESSION['error'] = 'Nome de utilizador já existe!';
            header('Location: change-username.php');
            exit;
        } else {
            if($stmt = $con->prepare('UPDATE accounts SET username = ? WHERE id = ?'))
            {
                $_SESSION['name'] = $_POST['username'];
                $stmt->bind_param('si', $_POST['username'], $_SESSION['id']);
                $stmt->execute();
                $stmt->close();
                $_SESSION['success'] = 'Nome de utilizador alterado com sucesso!';
                header('Location: profile.php');
                exit;
            } else {
                $_SESSION['error'] = 'Erro ao alterar nome de utilizador!';
                header('Location: change-username.php');
                exit;
            }
        }
    }
    else if($_POST['changetype'] == 'password') {
        if($stmt = $con->prepare('SELECT password FROM accounts WHERE id = ?')) {
            $stmt->bind_param('i', $_SESSION['id']);
            $stmt->execute();
            $stmt->store_result();
            if($stmt->num_rows > 0) {
                $stmt->bind_result($password_hash);
                $stmt->fetch();
                $password = $_POST['password'];
                if(password_verify($password, $password_hash)) {
                    if($stmt = $con->prepare('UPDATE accounts SET password = ? WHERE id = ?')) {
                        $stmt->bind_param('si', password_hash($_POST['newpassword'], PASSWORD_DEFAULT), $_SESSION['id']);
                        $stmt->execute();
                        $stmt->close();
                        $_SESSION['success'] = 'Palavra-passe alterada com sucesso!';
                        header('Location: profile.php');
                        exit;
                    } else {
                        $_SESSION['error'] = 'Erro ao alterar palavra-passe!';
                        header('Location: change-password.php');
                        exit;
                    }
                } else {
                    $_SESSION['error'] = 'Palavra-passe incorreta!';
                    header('Location: change-password.php');
                    exit;
                }
            } else {
                $_SESSION['error'] = 'Erro ao alterar palavra-passe!';
                header('Location: change-password.php');
                exit;
            }
        }
    } else {
        $_SESSION['error'] = 'Tipo não existente!';
        header('Location: profile.php');
        exit;
    }
} else {
    header('Location: profile.php');
    exit;
}
?>