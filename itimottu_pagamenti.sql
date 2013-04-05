-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generato il: Apr 05, 2013 alle 23:00
-- Versione del server: 5.0.96-community-log
-- Versione PHP: 5.3.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `DB-name`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `esito_richiesta`
--

CREATE TABLE IF NOT EXISTS `esito_richiesta` (
  `order_id` varchar(18) NOT NULL,
  `esito` tinyint(1) NOT NULL,
  `data_esito` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `richieste_comodato`
--

CREATE TABLE IF NOT EXISTS `richieste_comodato` (
  `data_ins` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `nome` varchar(75) NOT NULL,
  `classe` varchar(2) NOT NULL,
  `order_id` varchar(18) NOT NULL,
  `importo` int(5) NOT NULL,
  `email` varchar(50) NOT NULL,
  `session_id` varchar(26) NOT NULL,
  PRIMARY KEY  (`order_id`),
  KEY `nome` (`nome`,`classe`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `esito_richiesta`
--
ALTER TABLE `esito_richiesta`
  ADD CONSTRAINT `esito_richiesta_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `richieste_comodato` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
