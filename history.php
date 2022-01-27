<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
	header('Location: /');
	exit;
}

if(!empty($_GET['type'])) {
	$type = $_GET['type'];
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
			<h2>Historico<?php
			if(isset($type)) {
				if($type == "sell") {
					echo " de Vendas";
				} else if($type == "buy") {
					echo " de Compras";
				} else if($type == "withdraw") {
					echo " de Retiradas";
				} else if($type == "deposit") {
					echo " de Depósitos";
				} else {
					echo " de Transações";
				}
			} else {
				echo " de Transações";
			}?></h2>
	
			<div class="card-text bg-dark text-white">
				<div class="btn-group" role="group">
					<button class="btn btn-primary" onclick="window.location.href='/history.php?type=all'">Todas</button>
					<button class="btn btn-primary" onclick="window.location.href='/history.php?type=deposit'">Depósitos</button>
					<button class="btn btn-primary" onclick="window.location.href='/history.php?type=withdraw'">Retiradas</button>
					<button class="btn btn-primary" onclick="window.location.href='/history.php?type=sell'">Vendas</button>
					<button class="btn btn-primary" onclick="window.location.href='/history.php?type=buy'">Compras</button>
				</div>
			</div>
				<table class="table table-striped table-dark">
					<thead>
						<tr>
						<th scope="col">ID</th>
						<th scope="col">Tipo</th>
						<th scope="col">Valor</th>
						<th scope="col">Moeda</th>
						<th scope="col">Metodo</th>
						</tr>
					</thead>
					<?php
						include_once('db.php');
						include_once('functions/f2s.php');

						$sequle = "SELECT id, shortname FROM cryptos";
						$result = $con->query($sequle);
						$cryptos = array();
						while($row = $result->fetch_assoc()) {
							$cryptos[$row['id']] = $row['shortname'];
						}

						if(isset($type)) {
							if($type == "all") {
								$sql = "SELECT transaction_id, userid, type, value, coin, method FROM transactions WHERE userid = ? ORDER BY transaction_id DESC";
								if($stmt = $con->prepare($sql)) {
									$stmt = $con->prepare($sql);
									$stmt->bind_param('i', $_SESSION['id']);
									$stmt->execute();
									$stmt->bind_result($trid, $userid, $selecttype, $value, $coin, $method);
									while($stmt->fetch()) {
										echo "<tr>";
										echo "<td>".$trid."</td>";
										
										switch($selecttype) {
											case "sell":
												echo "<td>Venda</td>";
												break;
											case "buy":
												echo "<td>Compra</td>";
												break;
											case "withdraw":
												echo "<td>Retirada</td>";
												break;
											case "deposit":
												echo "<td>Depósito</td>";
												break;
											default:
												echo "<td></td>";
												break;
										}
										
										echo "<td>".f2s($value)."</td>";
										
										$none = false;
										foreach($cryptos as $id => $shortname) {
											if($coin == $id) {
												$none = true;
												echo "<td>".$shortname."</td>";
											}
										}
										
										if(!$none) {
											echo '<td>USD<i class="fas fa-dollar-sign"></i></td>';
										}

										switch($method) {
											case "gpay" : echo "<td>Google Pay</td>"; break;
											case "paypal" : echo "<td>Paypal</td>"; break;
											case "card" : echo "<td>Cartão Visa/Mastercard</td>"; break;
											case "mbway" : echo "<td>MbWay</td>"; break;
											case "bank" : echo "<td>Transferência Bancária</td>"; break;
											default : echo "<td></td>"; break;
										}
										echo "</tr>";
									}
								}
							} else {
								$sql = "SELECT transaction_id, userid, type, value, coin, method FROM transactions WHERE userid = ? AND type = ? ORDER BY transaction_id DESC";
							if($stmt = $con->prepare($sql)) {
								$stmt = $con->prepare($sql);
								$stmt->bind_param('is', $_SESSION['id'], $type);
								$stmt->execute();
								$stmt->bind_result($trid, $userid, $selecttype, $value, $coin, $method);
								while($stmt->fetch()) {
									echo "<tr>";
									echo "<td>".$trid."</td>";
									
									switch($selecttype) {
										case "sell":
											echo "<td>Venda</td>";
											break;
										case "buy":
											echo "<td>Compra</td>";
											break;
										case "withdraw":
											echo "<td>Retirada</td>";
											break;
										case "deposit":
											echo "<td>Depósito</td>";
											break;
										default:
											echo "<td></td>";
											break;
									}
									
									echo "<td>".f2s($value)."</td>";
									
									$none = false;
									foreach($cryptos as $id => $shortname) {
										if($coin == $id) {
											$none = true;
											echo "<td>".$shortname."</td>";
										}
									}
									
									if(!$none) {
										echo '<td>USD<i class="fas fa-dollar-sign"></i></td>';
									}

									switch($method) {
										case "gpay" : echo "<td>Google Pay</td>"; break;
										case "paypal" : echo "<td>Paypal</td>"; break;
										case "card" : echo "<td>Cartão Visa/Mastercard</td>"; break;
										case "mbway" : echo "<td>MbWay</td>"; break;
										case "bank" : echo "<td>Transferência Bancária</td>"; break;
										default : echo "<td></td>"; break;
									}
									echo "</tr>";
								}
							}
							}
						} else {
							$sql = "SELECT transaction_id, userid, type, value, coin, method FROM transactions WHERE userid = ? ORDER BY transaction_id DESC";
							if($stmt = $con->prepare($sql)) {
								$stmt = $con->prepare($sql);
								$stmt->bind_param('i', $_SESSION['id']);
								$stmt->execute();
								$stmt->bind_result($trid, $userid, $selecttype, $value, $coin, $method);
								while($stmt->fetch()) {
									echo "<tr>";
									echo "<td>".$trid."</td>";
									
									switch($selecttype) {
										case "sell":
											echo "<td>Venda</td>";
											break;
										case "buy":
											echo "<td>Compra</td>";
											break;
										case "withdraw":
											echo "<td>Retirada</td>";
											break;
										case "deposit":
											echo "<td>Depósito</td>";
											break;
										default:
											echo "<td></td>";
											break;
									}
									
									echo "<td>".f2s($value)."</td>";
									
									$none = false;
									foreach($cryptos as $id => $shortname) {
										if($coin == $id) {
											$none = true;
											echo "<td>".$shortname."</td>";
										}
									}
									
									if(!$none) {
										echo '<td>USD<i class="fas fa-dollar-sign"></i></td>';
									}

									switch($method) {
										case "gpay" : echo "<td>Google Pay</td>"; break;
										case "paypal" : echo "<td>Paypal</td>"; break;
										case "card" : echo "<td>Cartão Visa/Mastercard</td>"; break;
										case "mbway" : echo "<td>MbWay</td>"; break;
										case "bank" : echo "<td>Transferência Bancária</td>"; break;
										default : echo "<td></td>"; break;
									}
									echo "</tr>";
								}
							}
						}
					?>
				</table>
		</div>
	</body>
</html>