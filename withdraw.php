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
            <p class="card-text bg-dark text-white">Levantamento</p>
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
                <form action="confirmwithraw.php" method="post">
                    <label for="value">
                        <h6>Valor <i class="fas fa-dollar-sign"></i></h6>
                    </label>
                    <br>
					<div class="input-group">
                    	<input class="form-control" type="number" name="value" placeholder="Valor" id="value" required min="1" class="form-label">
						<div class="input-group-append">
                            <span class="input-group-text">$</span>
                        </div>
					</div>
					<br>
                    <label for="depmethod">Selecione um metodo de pagamento:</label>
                    <select class="form-select" id="method" name="method">
                        <option selected value="bank">Transferência bancária</option>
                        <option value="mbway">MBWay</option>
                        <option value="card">Cartão Visa/Mastercard</option>
                        <option value="paypal">Paypal</option>
                    </select>
                    <br>
                    <button type="submit" class="btn btn-primary">Retirar</button>
                </form>
            </div>
		</div>
	</body>
</html>