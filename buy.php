<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: /');
	exit;
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>CryptoScam</title>
        <link rel="icon" href="img/logo.png">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" integrity="sha512-GQGU0fMMi238uA+a/bdWJfpUGKUkBdgfFdgBm72SUQ6BeyWjoY/ton0tEjH+OSH9iP4Dfh+7HM0I9f5eR0L/4w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
		<link href="css/style.css" rel="stylesheet" type="text/css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js" integrity="sha512-n/4gHW3atM3QqRcbCn6ewmpxcLAHGaDjpEBu4xZd47N0W2oQ+6q7oc3PXstrJYXcbNU1OHdQ1T7pAP+gi5Yu8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>	
        <script>
            function verify(num) {
                <?php
                include_once('db.php');
                $sql = "SELECT id, shortname FROM `cryptos`";
                $stmt = $con->prepare($sql);
                $stmt->execute();
                $stmt->bind_result($id, $shortname);
                while($stmt->fetch()) {
                    echo 'if(num == ' . $id . ') {
                        return "' . $shortname . '"
                    }';
                }
                ?>
            }

            function verifyvalue(type, coin) {
                if(type == "coinusd") {
                    <?php
                        include_once('db.php');
                        $sql = "SELECT id, shortname FROM `cryptos`";
                        $stmt = $con->prepare($sql);
                        $stmt->execute();
                        $stmt->bind_result($id, $shortname);
                        while($stmt->fetch()) {
                        
                        $ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, "https://api.binance.com/api/v3/ticker/price?symbol=". $shortname ."USDT");
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						$output = curl_exec($ch);
						curl_close($ch);
						$output = json_decode($output, true);
						$price = $output['price'];
						$val = 1 / $price;
                        
                        echo 'if(coin == ' . $id . ') {
                            return ' . $val . ';
                        }';
                        }
                    ?>
                } else if(type == "usdcoin") {
                    <?php
                        include_once('db.php');
                        $sql = "SELECT id, shortname FROM `cryptos`";
                        $stmt = $con->prepare($sql);
                        $stmt->execute();
                        $stmt->bind_result($id, $shortname);
                        while($stmt->fetch()) {
                        
                        $ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, "https://api.binance.com/api/v3/ticker/price?symbol=". $shortname ."USDT");
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						$output = curl_exec($ch);
						curl_close($ch);
						$output = json_decode($output, true);
						$price = $output['price'];
						$val = $price;

                        echo 'if(coin == ' . $id . ') {
                            return ' . $val . ';
                        }';
                    }
                    ?>
                }
            }

            $(document).ready(function() {
                var d = $(`#coin`).val();

                var coin = $('#coin').val();
                $("#idrk").html(verify(coin));
                <?php
                if(isset($_GET['amount'])) {
                    echo '$("#value").val(' . $_GET['amount'] . ');';
                }
                ?>
            });

            async function change() {
                var coin = $('#coin').val();
                $("#idrk").html(verify(coin));

                var d = $(`#coin`).val();
                var response = verifyvalue("coinusd", d);
                var value = $(`#value`).val();
                var total = value * response;
                $(`#total`).val(total);
            }

            function valuechange() {
                var d = $(`#coin`).val();
                var response = verifyvalue("coinusd", d);
                var value = $(`#value`).val();
                var total = value * response;
                $(`#total`).val(total);
            }

            function valuechange2() {
                var d = $(`#coin`).val();
                var response = verifyvalue("usdcoin", d);
                var value = $(`#total`).val();
                var total = value * response;
                $(`#value`).val(total);
            }
        </script>
    </head>
	<body class="loggedin">
	<nav class="navtop">
			<div>
				<h1><a href="/"><i class="fas fa-home"></i>CryptoScam</a></h1>
				<a href="sell.php"><i class="fab fa-bitcoin"></i></i>Vender</a>
				<a href="buy.php"><i class="fab fa-bitcoin"></i></i>Comprar</a>
				<a href="withdraw.php"><i class="far fa-money-bill-alt"></i>Retirar</a>				<a href="deposit.php"><i class="fas fa-money-bill-alt"></i>Deposito</a>
                <a href="profile.php"><i class="fas fa-user-circle"></i>Perfil</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Terminar Sessão</a>
			</div>
		</nav>
		<div class="content">
			<h2>Bem vindo de volta, <?=$_SESSION['name']?>!</h2>
            <p class="card-text bg-dark text-white">Comprar Cryptomoedas</p>
                <?php
                if(!empty($_SESSION['success'])) {
					echo '<div class="alert alert-success" role="alert">'. $_SESSION['success'] . '</div>';
					unset($_SESSION['success']);
				}
				else if(!empty($_SESSION['error'])) {
					echo '<div class="alert alert-danger" role="alert">'. $_SESSION['error'] . '</div>';
					unset($_SESSION['error']);
				}
                ?>
                <div class="card-group bg-dark text-white"> 
                <form action="confirmbuy.php" method="post">
                    <label for="value">
                        <h6>Valor <i class="fas fa-dollar-sign"></i></h6>
                    </label>
                    <br>
                    <div class="input-group">
                        <input class="form-control" onchange="valuechange()" type="number" name="value"  placeholder="Valor" id="value" required step="0.000000000000000001" min="0" max="99999999999999999999999" class="form-label">
                        <div class="input-group-append">
                            <span class="input-group-text">$</span>
                        </div>
                    </div>
                    <br>
                    <!-- <fieldset disabled> -->
                    <label for="total">
                        <h6>Total</h6>
                    </label>
                    <div class="input-group">
                        <input class="form-control" onchange="valuechange2()" type="number" step="0.000000000000000001" min="0" max="9999" name="total" placeholder="Total" id="total" required class="form-label">
                        <div class="input-group-append">
                            <span id="idrk" class="input-group-text">coin_name</span>
                        </div>
                    </div>
                    <!-- </fieldset> -->
                    <br>
                    <label for="coin">Selecione a moeda:</label>
                    <select onchange="change()" class="form-select" id="coin" name="coin">
                        <?php
                        include_once('db.php');
                        if(isset($_GET['coin'])) {
                            $coin = $_GET['coin'];
                        }
                        $sql = "SELECT id, name, shortname FROM cryptos";
                        if($stmt = $con->prepare($sql)) {
                            $stmt->execute();
                            $stmt->store_result();
                            $stmt->bind_result($id, $name, $shortname);
                            while($stmt->fetch()) {
                                if(isset($coin)) {
                                    if($id == $coin) {
                                        echo '<option value="'.$id.'" selected>'.$name.'</option>';
                                    } else {
                                        echo '<option value="'.$id.'">'.$name.'</option>';
                                    }
                                } else {
                                    echo '<option value="'.$id.'">'.$name.'</option>';
                                }
                            }
                        }
                        ?>
                    </select>
                    <br>
                    <button type="submit" class="btn btn-primary">Comprar</button>
                </form>
            </div>
		</div>
	</body>
</html>