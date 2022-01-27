<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
	header('Location: /');
	exit;
}

include_once('db.php');

if($_SERVER['REQUEST_METHOD'] == 'POST') { 
    $sql = "SELECT usd FROM `accounts` WHERE `id` = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('i', $_SESSION['id']);
    $stmt->execute();
    $stmt->bind_result($usd);
    $stmt->fetch();
    $stmt->close();
    $balance = $usd;
    $value = $_POST['value'];

    if(empty($_POST['value'])) {
        $_SESSION['error'] = 'Por favor, insira um valor.';
        header('Location: withdraw.php');
        exit;
    } else if(!is_numeric($_POST['value'])) {
        $_SESSION['error'] = 'Por favor, insira um valor.';
        header('Location: withdraw.php');
        exit;
    } else if(empty($_POST['method'])) {
        $_SESSION['error'] = 'Por favor, selecione um metodo de pagamento.';
        header('Location: withdraw.php');
        exit;
    } else if($_POST['value'] < 0) {
        $_SESSION['error'] = 'Por favor, insira um valor.';
        header('Location: withdraw.php');
        exit;
    } else if($value <= $balance) {
        // $value = $_POST['value'];
        $method = $_POST['method'];
        $userid = $_SESSION['id'];
        $sql3 = "INSERT INTO `transactions` (`userid`, `type`, `value`, `method`) VALUES (?, ?, ?, ?)";
        $stmt3 = $con->prepare($sql3);
        $type = 'withdraw';
        $stmt3->bind_param('isds', $userid, $type, $value, $method);
        $stmt3->execute();
        $stmt3->close();
        $sql2 = "Update `accounts` SET `usd` = `usd` - ? WHERE `id` = ?";
        $stmt2 = $con->prepare($sql2);
        $stmt2->bind_param('ii', $value, $userid);
        $stmt2->execute();
        $stmt2->close();
        if($con) {
            $_SESSION['success'] = 'Levantamento efetuado com sucesso!';
            header('Location: withdraw.php');
            exit;
        } else {
            $_SESSION['error'] = 'Erro ao efetuar levantamento.';
            header('Location: withdraw.php');
            exit;
        }
    } else if($value > $balance) {
        $_SESSION['error'] = 'Saldo insuficiente.';
        header('Location: withdraw.php');
        exit;
    }
}
?>