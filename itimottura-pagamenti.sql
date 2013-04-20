-- phpMyAdmin SQL Dump
-- version 3.5.4
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generato il: Apr 20, 2013 alle 16:51
-- Versione del server: 5.5.28
-- Versione PHP: 5.4.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+01:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `itimottura`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `alunno`
--

CREATE TABLE IF NOT EXISTS `alunno` (
  `id` varchar(18) NOT NULL,
  `nome` varchar(75) NOT NULL,
  `classe` varchar(2) NOT NULL,
  `email` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `azienda`
--

CREATE TABLE IF NOT EXISTS `azienda` (
  `id` varchar(18) CHARACTER SET utf8 NOT NULL,
  `nome` varchar(75) CHARACTER SET utf8 NOT NULL,
  `email` varchar(50) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `causale`
--

CREATE TABLE IF NOT EXISTS `causale` (
  `id` varchar(18) NOT NULL,
  `body` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `esito_richiesta`
--

CREATE TABLE IF NOT EXISTS `esito_richiesta` (
  `order_id` varchar(18) NOT NULL,
  `esito` tinyint(1) NOT NULL,
  `data_esito` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `richieste_pagamento`
--

CREATE TABLE IF NOT EXISTS `richieste_pagamento` (
  `data_ins` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `order_id` varchar(18) NOT NULL,
  `importo` int(5) NOT NULL,
  `session_id` varchar(26) NOT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `alunno`
--
ALTER TABLE `alunno`
  ADD CONSTRAINT `alunno_ibfk_1` FOREIGN KEY (`id`) REFERENCES `richieste_pagamento` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `azienda`
--
ALTER TABLE `azienda`
  ADD CONSTRAINT `azienda_ibfk_2` FOREIGN KEY (`id`) REFERENCES `richieste_pagamento` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `causale`
--
ALTER TABLE `causale`
  ADD CONSTRAINT `causale_ibfk_2` FOREIGN KEY (`id`) REFERENCES `richieste_pagamento` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `esito_richiesta`
--
ALTER TABLE `esito_richiesta`
  ADD CONSTRAINT `esito_richiesta_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `richieste_pagamento` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
