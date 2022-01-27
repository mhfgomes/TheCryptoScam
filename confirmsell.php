<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: /');
    exit;
}

include_once('db.php');

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sql = "SELECT value FROM `useracc` WHERE `userid` = ? AND `cryptoid` = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('id', $_SESSION['id'], $_POST['coin']);
    $stmt->execute();
    $stmt->bind_result($usd);
    $stmt->fetch();
    $stmt->close();
    $balance = $usd;

    $value = $_POST['value'];
    $crypto_id = $_POST['coin'];

    $sql = "SELECT shortname, name FROM `cryptos` WHERE `id` = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('i', $crypto_id);
    $stmt->execute();
    $stmt->bind_result($crypto_name, $crypto_name2);
    $stmt->fetch();
    $stmt->close();

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.binance.com/api/v3/ticker/price?symbol=" . $crypto_name . "USDT");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    $output = json_decode($output, true);
    $price = $output['price'];
    $val = $price * $value;

    if(empty($_POST['value'])) {
        $_SESSION['error'] = 'Por favor, insira um valor.';
        header('Location: sell.php');
        exit;
    } else if(!is_numeric($_POST['value'])) {
        $_SESSION['error'] = 'Por favor, insira um valor.';
        header('Location: sell.php');
        exit;
    } else if($_POST['value'] < 0) {
        $_SESSION['error'] = 'Por favor, insira um valor.';
        header('Location: sell.php');
        exit;
    } else if(empty($_POST['coin'])) {
        $_SESSION['error'] = 'Por favor, selecione um metodo de pagamento.';
        header('Location: sell.php');
        exit;
    } else if($value <= $balance) {
        $coin = $_POST['coin'];
        $userid = $_SESSION['id'];
        $sql3 = "INSERT INTO `transactions` (`userid`, `type`, `value`, `coin`) VALUES (?, ?, ?, ?)";
        $stmt3 = $con->prepare($sql3);
        $type = 'sell';
        $stmt3->bind_param('isdi', $userid, $type, $value, $crypto_id);
        $stmt3->execute();
        $stmt3->close();

        $sql2 = "UPDATE `useracc` SET `value` = `value` - ? WHERE `userid` = ? AND `cryptoid` = ?";
        $stmt2 = $con->prepare($sql2);
        $stmt2->bind_param('dii', $value, $userid, $crypto_id);
        $stmt2->execute();
        $stmt2->close();

        $sql3 = "UPDATE `accounts` SET `usd` = `usd` + ? WHERE `id` = ?";
        $stmt3 = $con->prepare($sql3);
        $stmt3->bind_param('di', $val, $userid);
        $stmt3->execute();
        $stmt3->close();

        $_SESSION['success'] = 'Venda realizada com sucesso!';
        header('Location: sell.php');
        exit;

    } else {
        include_once('functions/f2s.php');
        $_SESSION['error'] = 'Não Possui ' . $crypto_name2 . ' suficiente.' . '<br>' . 'Seu saldo atual é de ' . f2s($balance) . ' ' . $crypto_name2;
        header('Location: sell.php');
        exit;
    }
}
?>