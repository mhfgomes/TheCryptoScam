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
		<title>CrytpoScam</title>
		<link rel="icon" href="img/logo.png">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" integrity="sha512-GQGU0fMMi238uA+a/bdWJfpUGKUkBdgfFdgBm72SUQ6BeyWjoY/ton0tEjH+OSH9iP4Dfh+7HM0I9f5eR0L/4w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
		<link href="css/style.css" rel="stylesheet" type="text/css">
	</head>
	<body class="loggedin">
	<nav class="navtop">
			<div>
				<h1><a href="/"><i class="fas fa-home"></i>CrytpoScam</a></h1>
				<a href="sell.php"><i class="fab fa-bitcoin"></i></i>Vender</a>
				<a href="buy.php"><i class="fab fa-bitcoin"></i></i>Comprar</a>
				<a href="withdraw.php"><i class="far fa-money-bill-alt"></i>Retirar</a>				<a href="deposit.php"><i class="fas fa-money-bill-alt"></i>Deposito</a>
                <a href="profile.php"><i class="fas fa-user-circle"></i>Perfil</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Terminar Sessão</a>
			</div>
		</nav>
		<div class="content">
			<h2>Bem vindo de volta, <?=$_SESSION['name']?>!</h2>
            <p class="card-text bg-dark text-white">Deposito</p>
                <?php
				$none = false;
                if(!empty($_SESSION['success'])) {
					echo '<div class="alert alert-success" role="alert">'. $_SESSION['success'] . '</div>';
					unset($_SESSION['success']);
				}
				else if(!empty($_SESSION['error'])) {
					echo '<div class="alert alert-danger" role="alert">'. $_SESSION['error'] . '</div>';
					unset($_SESSION['error']);
				}

				if($_SERVER['REQUEST_METHOD'] == 'POST') {
					if(empty($_POST['val'])) {
						$none = true;
						echo '<div class="alert alert-danger" role="alert">Por favor, insira um valor.</div>';
					} else if(!is_numeric($_POST['val'])) {
						$none = true;
						echo '<div class="alert alert-danger" role="alert">Por favor, insira um valor valido.</div>';
					} else if($_POST['val'] < 10) {
						$none = true;
						echo '<div class="alert alert-danger" role="alert">O valor minimo é 10.</div>';
					} else if(empty($_POST['depmethod'])) {
						$none = true;
						echo '<div class="alert alert-danger" role="alert">Por favor, selecione um metodo de pagamento.</div>';
					} else {
						unset($_SESSION['depmethod']);
						unset($_SESSION['value']);
						$value = $_POST['val'];
						$_SESSION['value'] = $_POST['val'];
						$depmethod = $_POST['depmethod'];
						$_SESSION['depmethod'] = $_POST['depmethod'];
						echo '<div class="card-group bg-dark text-white"> 
						<form action="confirmdep.php" method="post">
						<label for="value">
							<h6>Valor <i class="fas fa-dollar-sign"></i></h6>
						</label>
						<br>
						<fieldset disabled>
						<div class="input-group">
							<input class="form-control" type="number" name="value" placeholder="Valor (minimo 10$USD)" value="' . $_POST["val"] . '" id="value" required min="1" class="form-label">
							<div class="input-group-append">
                            	<span class="input-group-text">$</span>
                        	</div>
						</div>
						<br>
						<label for="depmethod">Selecione um metodo de pagamento:</label>
						<select class="form-select" id="depmethod" name="depmethod">';
						if($_POST["depmethod"] == 'mbway') {
							echo '<option value="mbway" selected>MBway</option>';
							echo '</select>
							</fieldset>
							<br>
							<label for="phone">
								<h6><i class="fas fa-mobile"></i> Numero de telemovel</h6>
							</label>
							<br>
							<input class="form-control" type="text" name="phone" placeholder="Numero de Telemovel" id="phone" min="9" required class="form-label">
							<br>';
						} else if ($_POST["depmethod"] == 'card') {
							echo '<option selected value="card">Cartão Visa/Mastercard</option>';
							echo '</select>
							</fieldset>
							<br>
							<label for="cardnr">
								<h6><i class="fas fa-credit-card"></i> Dados do cartão</h6>
							</label>
							<br>
							<input class="form-control" type="text" name="cardnr" placeholder="Numero do cartão" id="cardnr" min="16" max="16" required class="form-label">
							<br>
							<input class="form-control" type="text" name="validade" placeholder="Validade do cartão" id="validade" required class="form-label">
							<br>
							<input class="form-control" type="text" name="cvv" placeholder="CVV" id="cvv" min="3" max="3" required class="form-label">
							<br>';
						} else if($_POST["depmethod"] == 'gpay') {
							echo '<option value="gpay">Google Pay</option>';
							echo '</select>
							</fieldset>
							<br>';
						} else if($_POST['depmethod'] == 'paypal') {
							echo '<option selected value="paypal">Paypal</option>';
							echo '</select>
							</fieldset>
							<br>
							<label for="emailpp">
								<h6><i class="fab fa-paypal"></i>Email do PayPal</h6>
							</label>
							<br>
							<input class="form-control" type="email" name="emailpp" placeholder="Email do PayPal" id="emailpp" min="9" required class="form-label">
							<br>';
						}
						echo '<button type="submit" class="btn btn-primary">Confirmar Deposito</button>
						</form>
						</div>';
					}
				} else {
					$none = true;
				}
                if ($none) {
				echo '<div class="card-group bg-dark text-white"> 
                <form action="deposit.php" method="post">
                    <label for="val">
                        <h6>Valor <i class="fas fa-dollar-sign"></i></h6>
                    </label>
                    <br>
                    <div class="input-group">
						<input class="form-control" type="number" name="val" placeholder="Valor (minimo 10$USD)" id="val" required min="1" class="form-label">
						<div class="input-group-append">
                            <span class="input-group-text">$</span>
                        </div>
					</div>
					<br>
					<label for="depmethod">Selecione um metodo de pagamento:</label>
                    <select class="form-select" id="depmethod" name="depmethod">
                        <option selected value="mbway">MBWay</option>
                        <option value="card">Cartão Visa/Mastercard</option>
                        <option value="gpay">Google Pay</option>
                        <option value="paypal">Paypal</option>
                    </select>
                    <br>
                    <button type="submit" class="btn btn-primary">Depositar</button>
                </form>
				</div>';
				}
				?>
		</div>
	</body>
</html>