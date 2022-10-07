-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 07-Out-2022 às 02:01
-- Versão do servidor: 10.4.24-MariaDB
-- versão do PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `bco_services`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `servicos`
--

CREATE TABLE `servicos` (
  `id` int(11) NOT NULL,
  `nome_usuario` varchar(255) NOT NULL,
  `item_servico` varchar(255) NOT NULL,
  `descricao_servico` text NOT NULL,
  `data_insercao` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `servicos`
--

INSERT INTO `servicos` (`id`, `nome_usuario`, `item_servico`, `descricao_servico`, `data_insercao`) VALUES
(1, 'Rodrigo', 'teste', 'tetetete', '2022-09-30 15:39:57'),
(2, 'Rodrigo', 'teste', 'tetetete', '2022-09-30 15:55:23'),
(3, 'Rodrigo', 'Manutenção de via pública4', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. ', '2022-09-30 15:56:22'),
(4, 'Fernando', 'rua', 'Teste de et consectetur adipisicing elit', '2022-09-30 16:15:30'),
(5, 'Jose', 'Teste de Paginação', 'vhbscsncnds.ncs.d', '2022-09-30 17:26:11'),
(6, 'Rodrigo Oliveira Editado', 'Troca de lamapadas! Editado', 'Trocar as lampadas da rua Gaspar Editado', '2022-09-30 17:26:22'),
(7, 'Resolvendo paginação', 'paginacao', 'saxsalxpslxpsllxsalxp', '2022-09-30 17:28:19'),
(8, 'Manutenção Novo ', 'Manutenção Novo ', 'Manutenção Novo ', '2022-10-03 19:11:42'),
(9, 'Manutenção Novo ', 'Manutenção Novo ', 'Manutenção Novo ', '2022-10-03 19:11:54'),
(10, 'Rodrigo Oliveira', 'Troca de lamapadas!', 'Trocar as lampadas da rua Gaspar', '2022-10-05 01:21:20'),
(11, 'Rodrigo Oliveira', 'Troca de lamapadas!', 'Trocar as lampadas da rua Gaspar', '2022-10-05 01:21:36'),
(12, 'Rodrigo Oliveira', 'Troca de lamapadas!', 'Trocar as lampadas da rua Gaspar', '2022-10-05 13:47:56');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `servicos`
--
ALTER TABLE `servicos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `servicos`
--
ALTER TABLE `servicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
