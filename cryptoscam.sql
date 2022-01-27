-- --------------------------------------------------------
-- Anfitrião:                    127.0.0.1
-- Versão do servidor:           10.4.22-MariaDB - mariadb.org binary distribution
-- SO do servidor:               Win64
-- HeidiSQL Versão:              11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- A despejar estrutura da base de dados para cryptoscam
DROP DATABASE IF EXISTS `cryptoscam`;
CREATE DATABASE IF NOT EXISTS `cryptoscam` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `cryptoscam`;

-- A despejar estrutura para tabela cryptoscam.accounts
DROP TABLE IF EXISTS `accounts`;
CREATE TABLE IF NOT EXISTS `accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `perm` int(11) NOT NULL DEFAULT 0,
  `usd` decimal(20,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;

-- A despejar dados para tabela cryptoscam.accounts: ~4 rows (aproximadamente)
DELETE FROM `accounts`;
/*!40000 ALTER TABLE `accounts` DISABLE KEYS */;
INSERT INTO `accounts` (`id`, `username`, `password`, `email`, `perm`, `usd`) VALUES
	(2, 'mario', '$2y$10$d1L87QlX0Yas6B00Ssv1uuZKJXQt/cf2qSqaBqNJzYPUcjNJbi6aW', 'mhfgomes18@gmail.com', 0, 99998576465561.55),
	(6, 'test', '$2y$10$VSUh0mfEPy/hovvF3emCgOEY0t/CViJPWXix0wIYSl9PSIa9UsGMG', 'test@test.com', 0, 0.00),
	(7, 'mario2', '$2y$10$7kluvFln8l4izYTyinrpG.gCpvdPycgND/KhJ5Ppw1sgB7Xl7q8sy', 'mhfgomes18@gmail.com', 0, 0.00),
	(8, 'mario3', '$2y$10$z3cMSYnzh2StpO1EKpzUwOqyNX2KhUQVz9xkBRzKgy3eKpyEtkAEe', 'mhfgomes18@gmail.com', 0, 0.00),
	(9, 'mario000', '$2y$10$jipJeWBToz0CFdibAjQfOO88wcGOzQ0j91881SOnNvEqHwOTkuBNm', 'a@gmail.com', 0, 0.00);
/*!40000 ALTER TABLE `accounts` ENABLE KEYS */;

-- A despejar estrutura para tabela cryptoscam.cryptos
DROP TABLE IF EXISTS `cryptos`;
CREATE TABLE IF NOT EXISTS `cryptos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `shortname` varchar(50) NOT NULL,
  `img` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

-- A despejar dados para tabela cryptoscam.cryptos: ~6 rows (aproximadamente)
DELETE FROM `cryptos`;
/*!40000 ALTER TABLE `cryptos` DISABLE KEYS */;
INSERT INTO `cryptos` (`id`, `name`, `shortname`, `img`) VALUES
	(1, 'Bitcoin', 'BTC', 'https://cdn.discordapp.com/attachments/792453355154178078/932429445061554246/btc.png'),
	(2, 'Litecoin', 'LTC', 'https://cdn.discordapp.com/attachments/792453355154178078/932429444822487070/ltc.png'),
	(3, 'Ethereum', 'ETH', 'https://cdn.discordapp.com/attachments/792453355154178078/932429766651441152/eth.png'),
	(4, 'Monero', 'XMR', 'https://cdn.discordapp.com/attachments/792453355154178078/932429967093018654/xmr.png'),
	(5, 'Dogecoin', 'DOGE', 'https://cdn.discordapp.com/attachments/792453355154178078/932430179811336233/doge.png'),
	(6, 'Solana', 'SOL', 'https://cdn.discordapp.com/attachments/792453355154178078/932430602362306570/sol.png');
/*!40000 ALTER TABLE `cryptos` ENABLE KEYS */;

-- A despejar estrutura para tabela cryptoscam.transactions
DROP TABLE IF EXISTS `transactions`;
CREATE TABLE IF NOT EXISTS `transactions` (
  `transaction_id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `value` float NOT NULL,
  `coin` int(11) DEFAULT NULL,
  `method` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`transaction_id`) USING BTREE,
  KEY `FK_transactions_accounts` (`userid`),
  KEY `FK_transactions_cryptos` (`coin`),
  CONSTRAINT `FK_transactions_accounts` FOREIGN KEY (`userid`) REFERENCES `accounts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_transactions_cryptos` FOREIGN KEY (`coin`) REFERENCES `cryptos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

-- A despejar dados para tabela cryptoscam.transactions: ~8 rows (aproximadamente)
DELETE FROM `transactions`;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
INSERT INTO `transactions` (`transaction_id`, `userid`, `type`, `value`, `coin`, `method`) VALUES
	(1, 2, 'sell', 0.00000000000003798, 1, NULL),
	(2, 2, 'buy', 0.0000263195, 1, NULL),
	(3, 2, 'buy', 0.999861, 1, NULL),
	(4, 2, 'buy', 1.0003, 1, NULL),
	(5, 2, 'sell', 37977.6, 1, NULL),
	(6, 2, 'sell', 1, 1, NULL),
	(7, 2, 'withdraw', 50, NULL, 'bank'),
	(8, 2, 'deposit', 50, NULL, 'gpay');
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;

-- A despejar estrutura para tabela cryptoscam.useracc
DROP TABLE IF EXISTS `useracc`;
CREATE TABLE IF NOT EXISTS `useracc` (
  `cryptoid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `value` double NOT NULL DEFAULT 0,
  PRIMARY KEY (`cryptoid`,`userid`),
  KEY `FK_useracc_accounts` (`userid`),
  CONSTRAINT `FK_useracc_accounts` FOREIGN KEY (`userid`) REFERENCES `accounts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_useracc_cryptos` FOREIGN KEY (`cryptoid`) REFERENCES `cryptos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- A despejar dados para tabela cryptoscam.useracc: ~7 rows (aproximadamente)
DELETE FROM `useracc`;
/*!40000 ALTER TABLE `useracc` DISABLE KEYS */;
INSERT INTO `useracc` (`cryptoid`, `userid`, `value`) VALUES
	(1, 2, 0.0001920012345681954),
	(1, 7, 0.004292642610888259),
	(2, 2, 2.88489750676594e-54),
	(3, 2, 0.007717448765787006),
	(4, 2, 0.0663129973474801),
	(5, 2, 0.1426444271564833),
	(6, 2, 1.154324276327473);
/*!40000 ALTER TABLE `useracc` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
