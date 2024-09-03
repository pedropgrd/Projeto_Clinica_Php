-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Tempo de geração: 03/09/2024 às 03:40
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `db_clinica`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `agendamento`
--

CREATE TABLE `agendamento` (
  `id_agenda` int(11) NOT NULL,
  `id_secretaria` int(11) DEFAULT NULL,
  `id_paciente` int(11) DEFAULT NULL,
  `id_tipo_consulta` int(11) DEFAULT NULL,
  `id_dentista` int(11) DEFAULT NULL,
  `tipo_consulta` varchar(50) DEFAULT NULL,
  `descricao` varchar(50) DEFAULT NULL,
  `realizado` char(1) DEFAULT NULL,
  `horario` time DEFAULT NULL,
  `dt_agenda` date DEFAULT NULL,
  `preco_final_agendamento` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `agendamento`
--

INSERT INTO `agendamento` (`id_agenda`, `id_secretaria`, `id_paciente`, `id_tipo_consulta`, `id_dentista`, `tipo_consulta`, `descricao`, `realizado`, `horario`, `dt_agenda`, `preco_final_agendamento`) VALUES
(0, NULL, 1, 1, 1, 'Manutenção', 'Primeira visita', 'N', '17:05:00', '2024-08-30', 200.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `dentista`
--

CREATE TABLE `dentista` (
  `id_dentista` int(11) NOT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `sexo` char(1) DEFAULT NULL,
  `telefone` varchar(11) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `dentista`
--

INSERT INTO `dentista` (`id_dentista`, `nome`, `sexo`, `telefone`, `email`) VALUES
(1, 'Wagner Ferreira ', NULL, NULL, 'wagner.ferreira@gmail.com');

-- --------------------------------------------------------

--
-- Estrutura para tabela `paciente`
--

CREATE TABLE `paciente` (
  `id_paciente` int(11) NOT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `cpf` char(11) DEFAULT NULL,
  `dt_nascimento` date DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `sexo` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `paciente`
--

INSERT INTO `paciente` (`id_paciente`, `nome`, `cpf`, `dt_nascimento`, `telefone`, `sexo`) VALUES
(1, 'Juliana Saragioto ', '06142399198', '1900-04-23', '65996992347', 'F'),
(2, 'Marcos Paulo de Arruda', '06142399178', '2001-05-23', '65996992375', 'M');

-- --------------------------------------------------------

--
-- Estrutura para tabela `secretaria`
--

CREATE TABLE `secretaria` (
  `id_secretaria` int(11) NOT NULL,
  `id_clinica` int(11) DEFAULT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `sexo` char(1) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `secretaria`
--

INSERT INTO `secretaria` (`id_secretaria`, `id_clinica`, `nome`, `sexo`, `email`) VALUES
(1, NULL, 'Pedro', NULL, 'pedro.dev2074@gmail.com');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tipo_consulta`
--

CREATE TABLE `tipo_consulta` (
  `id_tipo_consulta` int(11) NOT NULL,
  `preco` decimal(5,2) DEFAULT NULL,
  `descricao` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tipo_consulta`
--

INSERT INTO `tipo_consulta` (`id_tipo_consulta`, `preco`, `descricao`) VALUES
(1, 100.00, 'Aparelho');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `cpf` char(11) NOT NULL,
  `senha` varchar(15) NOT NULL,
  `cargo` varchar(20) NOT NULL,
  `id_dentista` int(11) DEFAULT NULL,
  `id_secretaria` int(11) DEFAULT NULL,
  `admin` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `cpf`, `senha`, `cargo`, `id_dentista`, `id_secretaria`, `admin`) VALUES
(10, '06142399154', 'Familia24', 'Funcionario', NULL, 1, 'S'),
(11, '06142399153', 'Familia23', 'Dentista', 1, NULL, 'S');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `agendamento`
--
ALTER TABLE `agendamento`
  ADD PRIMARY KEY (`id_agenda`),
  ADD KEY `id_secretaria` (`id_secretaria`),
  ADD KEY `id_paciente` (`id_paciente`),
  ADD KEY `id_tipo_consulta` (`id_tipo_consulta`),
  ADD KEY `id_dentista` (`id_dentista`);

--
-- Índices de tabela `dentista`
--
ALTER TABLE `dentista`
  ADD PRIMARY KEY (`id_dentista`);

--
-- Índices de tabela `paciente`
--
ALTER TABLE `paciente`
  ADD PRIMARY KEY (`id_paciente`);

--
-- Índices de tabela `secretaria`
--
ALTER TABLE `secretaria`
  ADD PRIMARY KEY (`id_secretaria`);

--
-- Índices de tabela `tipo_consulta`
--
ALTER TABLE `tipo_consulta`
  ADD PRIMARY KEY (`id_tipo_consulta`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD KEY `id_dentista` (`id_dentista`),
  ADD KEY `id_secretaria` (`id_secretaria`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `dentista`
--
ALTER TABLE `dentista`
  MODIFY `id_dentista` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `paciente`
--
ALTER TABLE `paciente`
  MODIFY `id_paciente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `secretaria`
--
ALTER TABLE `secretaria`
  MODIFY `id_secretaria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `tipo_consulta`
--
ALTER TABLE `tipo_consulta`
  MODIFY `id_tipo_consulta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `agendamento`
--
ALTER TABLE `agendamento`
  ADD CONSTRAINT `agendamento_ibfk_1` FOREIGN KEY (`id_secretaria`) REFERENCES `secretaria` (`id_secretaria`),
  ADD CONSTRAINT `agendamento_ibfk_2` FOREIGN KEY (`id_paciente`) REFERENCES `paciente` (`id_paciente`),
  ADD CONSTRAINT `agendamento_ibfk_3` FOREIGN KEY (`id_tipo_consulta`) REFERENCES `tipo_consulta` (`id_tipo_consulta`),
  ADD CONSTRAINT `agendamento_ibfk_4` FOREIGN KEY (`id_dentista`) REFERENCES `dentista` (`id_dentista`);

--
-- Restrições para tabelas `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_dentista`) REFERENCES `dentista` (`id_dentista`),
  ADD CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`id_secretaria`) REFERENCES `secretaria` (`id_secretaria`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
