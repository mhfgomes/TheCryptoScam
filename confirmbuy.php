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
    $crypto_id = $_POST['coin'];

    $sql = "SELECT shortname FROM `cryptos` WHERE `id` = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('i', $crypto_id);
    $stmt->execute();
    $stmt->bind_result($crypto_name);
    $stmt->fetch();
    $stmt->close();

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.binance.com/api/v3/ticker/price?symbol=" . $crypto_name . "USDT");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    $output = json_decode($output, true);
    $price = $output['price'];
    $val = 1 / $price * $value;

    if(empty($_POST['value'])) {
        $_SESSION['error'] = 'Por favor, insira um valor.';
        header('Location: buy.php');
        exit;
    } else if(!is_numeric($_POST['value'])) {
        $_SESSION['error'] = 'Por favor, insira um valor.';
        header('Location: buy.php');
        exit;
    } else if($_POST['value'] < 0) {
        $_SESSION['error'] = 'Por favor, insira um valor.';
        header('Location: buy.php');
        exit;
    } else if(empty($_POST['coin'])) {
        $_SESSION['error'] = 'Por favor, selecione um metodo de pagamento.';
        header('Location: buy.php');
        exit;
    } else if($value <= $balance) {
        $coin = $_POST['coin'];
        $userid = $_SESSION['id'];
        $sql3 = "INSERT INTO `transactions` (`userid`, `type`, `value`, `coin`) VALUES (?, ?, ?, ?)";
        $stmt3 = $con->prepare($sql3);
        $type = 'buy';
        $stmt3->bind_param('isdi', $userid, $type, $val, $crypto_id);
        $stmt3->execute();
        $stmt3->close();
        
        $sql2 = "Update `accounts` SET `usd` = `usd` - ? WHERE `id` = ?";
        $stmt2 = $con->prepare($sql2);
        $stmt2->bind_param('di', $value, $userid);
        $stmt2->execute();
        $stmt2->close();

        $sql222 = "SELECT 1 FROM useracc WHERE userid = ? AND cryptoid = ?";
        $stmt222 = $con->prepare($sql222);
        $stmt222->bind_param('ii', $userid, $crypto_id);
        $stmt222->execute();
        $stmt222->store_result();

        if($stmt222->num_rows == 0) {
            $sql2 = "INSERT INTO `useracc` (`userid`, `cryptoid`, `value`) VALUES (?, ?, ?)";
            $stmt2 = $con->prepare($sql2);
            $stmt2->bind_param('iid', $userid, $crypto_id, $val);
            $stmt2->execute();
            $stmt2->close();
        } else {
            $sql2 = "UPDATE `useracc` SET `value` = `value` + ? WHERE `userid` = ? AND `cryptoid` = ?";
            $stmt2 = $con->prepare($sql2);
            $stmt2->bind_param('dii', $val, $userid, $crypto_id);
            $stmt2->execute();
            $stmt2->close();
        }

        $stmt222->close();

        if($con) {
            $_SESSION['success'] = 'Compra efetuada com sucesso!';
            header('Location: buy.php');
            exit;
        } else {
            $_SESSION['error'] = 'Erro ao efetuar Compra.';
            header('Location: buy.php');
            exit;
        }
    } else if($value > $balance) {
        $_SESSION['error'] = 'Saldo insuficiente.';
        header('Location: buy.php');
        exit;
    }
}
?>