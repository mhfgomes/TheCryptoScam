<?php
session_start();
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
		<?php
		if (!isset($_SESSION['loggedin'])) {
			echo '<div>
			<h1><a href="/"><i class="fas fa-home"></i>CrytpoScam</a></h1>
			<a href="login.php"><i class="fas fa-sign-in-alt"></i>Iniciar Sessão</a>
			<a href="register.php"><i class="fas fa-user-plus"></i>Criar Conta</a>
		</div>';
		} else {
			echo '<div>
			<h1><a href="/"><i class="fas fa-home"></i>CrytpoScam</a></h1>
			<a href="sell.php"><i class="fab fa-bitcoin"></i></i>Vender</a>
			<a href="buy.php"><i class="fab fa-bitcoin"></i></i>Comprar</a>
			<a href="withdraw.php"><i class="far fa-money-bill-alt"></i>Retirar</a>				<a href="deposit.php"><i class="fas fa-money-bill-alt"></i>Deposito</a>
			<a href="profile.php"><i class="fas fa-user-circle"></i>Perfil</a>
			<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Terminar Sessão</a>
		</div>';
		}
		?>
		</nav>
		<div class="content">
			<?php
			if (!isset($_SESSION['loggedin'])) {
				require_once('db.php');
				echo '<h2>Bem-vindo ao CryptoScam!</h2>
				<div class="bg-dark text-white">
					<p class="card-text bg-dark text-white">Compre e Venda Criptomoedas em minutos 
					<br>Faça o seu registo em minutos
					<br>E Junte-se à maior corretora de criptomoedas do mundo</p>
				</div>';
				if ($stmt = $con->prepare("SELECT name, shortname, img FROM cryptos")) {
					$stmt->execute();
					$stmt->bind_result($name, $shortname, $img);
					echo '<p class="card-text bg-dark text-white">Temos uma enorme diversidade de cryptomoedas</p>
					<div class="row row-cols-1 row-cols-md-3 g-4 bg-dark text-white">';
					while ($stmt->fetch()) {
						echo '<div class="card bg-dark text-white">
								<img src="' . $img . '" class="card-img-top">
								<div class="card-body">
									<h5 class="card-title">' . $name . '</h5>
								</div>
							</div>';
					}
					echo '</div>';
					echo '<div class="embed-responsive embed-responsive-16by9 bg-dark">';
					echo '<p class="card-text bg-dark text-white">Alguns videos para aprender mais sobre o mundo das cryptomoedas</p>';
					echo '<iframe class="embed-responsive-item" width="50%" height="269" src="https://www.youtube.com/embed/t3MU2YZpdv0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
					echo '<iframe class="embed-responsive-item" width="50%" height="269" src="https://www.youtube.com/embed/yazRonisDm8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
					echo '<iframe class="embed-responsive-item" width="50%" height="269" src="https://www.youtube.com/embed/DRc7Ej9zUYs" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
					echo '<iframe class="embed-responsive-item" width="50%" height="269" src="https://www.youtube.com/embed/jP3uzg0Suc4" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
					echo '</div>';
					$stmt->close();
				}
				echo '<p class="card-text bg-dark text-white">Comece a usar o CryptoScam, faça o seu <a href="register.php">registo</a> gratuito!</p>';
			}
			else
			{
				echo '<h2>Bem-vindo ao CryptoScam, ' . $_SESSION['name'] . '!</h2>
				<p class="card-text bg-dark text-white">Resumo Dos Seus Saldos</p>';
				require_once('db.php');
				require_once('functions/f2s.php');
				$totval = 0;
				if($stmt2 = $con->prepare('SELECT usd FROM accounts WHERE id = ?')) {
					$stmt2->bind_param('i', $_SESSION['id']);
					$stmt2->execute();
					$stmt2->bind_result($usd);
					$stmt2->fetch();
					$stmt2->close();
					echo('<p class="card-text bg-dark text-white">Fiat</p>');
					$totval += $usd;
					$sheesh = number_format((float)$usd, 2, '.', '');
					echo('<div class="card bg-dark text-white">');
					echo '<div class="card-body">';
					echo '<h5 class="card-title">Dolar</h5>';
					echo '<h6 class="card-subtitle mb-2 text-muted">USD</h6>';
					echo '<p class="card-text">' . f2s($sheesh) . ' <i class="fas fa-dollar-sign"></i></p>';
					$sheeesh = "'";
					echo '<div class="btn-group" role="group">';
					echo '<button class="btn btn-primary" onclick="window.location.href=' . $sheeesh . 'deposit.php' . $sheeesh . '">Depositar</button>';
					echo '<button class="btn btn-primary" onclick="window.location.href=' . $sheeesh . 'withdraw.php' . $sheeesh . '">Retirar</button>';
					echo '</div>';
					echo '</div>';
					echo '</div>';
				}

				if ($stmt = $con->prepare('SELECT cryptos.id, cryptos.name, cryptos.shortname, cryptos.img, useracc.value FROM cryptos INNER JOIN useracc ON useracc.cryptoid = cryptos.id WHERE useracc.userid = ? ORDER BY cryptos.shortname ASC')) {
					$stmt->bind_param('s', $_SESSION['id']);
					$stmt->execute();
					$meta = $stmt->result_metadata(); 
					
					while ($field = $meta->fetch_field()) 
					{ 
						$params[] = &$row[$field->name]; 
					}

					call_user_func_array(array($stmt, 'bind_result'), $params);
					$a = false;
					
					while ($stmt->fetch()) { 
						foreach($row as $key => $val) { 
							$a = true;
							$c[$key] = $val; 
						} 
						$result[] = $c; 
					} 

					$stmt->close();

					if ($a) {
						$first = true;
						$last = false;
						foreach($result as $res) {	
							if($first) {
								echo('<p class="card-text bg-dark text-white">Spot</p>');
								echo '<div class="row row-cols-1 row-cols-md-3 g-4 bg-dark text-white">';
								$first = false;
								$last = true;
							}

							$val = 0;

							if($res['shortname'] != 'USDT') {
								$ch = curl_init();
								curl_setopt($ch, CURLOPT_URL, "https://api.binance.com/api/v3/ticker/price?symbol=".$res['shortname']."USDT");
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
								$output = curl_exec($ch);
								curl_close($ch);
								$output = json_decode($output, true);
								$price = $output['price'];
								$val = $price * $res['value'];
							} else {
								$val = $res['value'];
							}

							$totval += $val;
							echo '<div class="card bg-dark text-white">';
							echo '<img class="card-img" src="' .$res['img'] .'">';
							echo '<div class="card-body">';
							echo '<h5 class="card-title">' . $res['name'] . '</h5>';
							echo '<h6 class="card-subtitle mb-2 text-muted">' . $res['shortname'] . '</h6>';
							echo '<p class="card-text">' . f2s(number_format((float)$res['value'], 8, '.', '')) . ' ' . $res['shortname'] . ' = ' . number_format((float)$val, 2, '.', '') . ' <i class="fas fa-dollar-sign"></i></p>';
							$p = "'";
							echo '<div class="btn-group" role="group">';
							echo '<button class="btn btn-primary" onclick="window.location.href=' . $p . 'sell.php?coin=' . $res['id'] . '&amount=' . f2s(number_format((float)$res['value'], 8, '.', '')) . $p . '">vender</button>';
							echo '<button class="btn btn-primary" onclick="window.location.href=' . $p . 'buy.php' . $p . '">Comprar</button>';
							echo '</div>';
							echo '</div>';
							echo '</div>';
						} 
						if($last) {
							echo('</div>');
						}
					}

					$totval = number_format((float)$totval, 2, '.', '');
					echo '<p class="card-text bg-dark text-white">Fiat + Spot</p>';
					echo '<div class="card bg-dark text-white">';					
					echo '<div class="card-body">';
					echo '<h5 class="card-title">' . 'Total' . '</h5>';
					echo '<h6 class="card-subtitle mb-2 text-muted">USD</h6>';
					echo '<p class="card-text">' . f2s($totval) . ' <i class="fas fa-dollar-sign"></i></p>';
					echo '</div>';
					echo '</div>';
				}
			}
			?>
		</div>
	</body>
</html>