<?php

use function PHPSTORM_META\type;

session_start();

if (!isset($_SESSION['loggedin'])) {
	header('Location: /');
	exit;
}

include_once('db.php');

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(empty($_SESSION['value'])) {
        $_SESSION['error'] = 'Por favor, insira um valor.';
        header('Location: deposit.php');
        exit;
    } else if(empty($_SESSION['depmethod'])) {
        $_SESSION['error'] = 'Por favor, selecione um metodo de pagamento.';
        header('Location: deposit.php');
        exit;
    } else if($_SESSION['value'] < 10) {
        $_SESSION['error'] = "O valor minimo é 10.";
        header('Location: deposit.php');
        exit;
    } else {
        $value = $_SESSION['value'];
        unset($_SESSION['value']);
        $depmethod = $_SESSION['depmethod'];
        unset($_SESSION['depmethod']);
        $userid = $_SESSION['id'];
        $type = 'deposit';
        $sql = "INSERT INTO `transactions` (`userid`, `type` , `value`, `method`) VALUES (?, ?, ?, ?)";
        $stmt3 = $con->prepare($sql);
        $stmt3->bind_param('isds', $userid, $type, $value, $depmethod);
        $stmt3->execute();
        $stmt3->close();
        $sql2 = "Update `accounts` SET `usd` = `usd` + ? WHERE `id` = ?";
        $stmt2 = $con->prepare($sql2);
        $stmt2->bind_param('ii', $value, $userid);
        $stmt2->execute();
        $stmt2->close();
        if($con) {
            $_SESSION['success'] = 'Deposito efetuado com sucesso!';
            header('Location: deposit.php');
            exit;
        } else {
            $_SESSION['error'] = 'Erro ao efetuar deposito.';
            header('Location: deposit.php');
            exit;
        }
    }
} else {
    header('Location: /');
    exit;
}

?>