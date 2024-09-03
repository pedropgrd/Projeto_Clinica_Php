
--   novo sql 2.o

-- Estrutura para tabela `agendamento`
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
  `preco_final_agendamento` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`id_agenda`),
  KEY `id_secretaria` (`id_secretaria`),
  KEY `id_paciente` (`id_paciente`),
  KEY `id_tipo_consulta` (`id_tipo_consulta`),
  KEY `id_dentista` (`id_dentista`),
  CONSTRAINT `agendamento_ibfk_1` FOREIGN KEY (`id_secretaria`) REFERENCES `secretaria` (`id_secretaria`),
  CONSTRAINT `agendamento_ibfk_2` FOREIGN KEY (`id_paciente`) REFERENCES `paciente` (`id_paciente`),
  CONSTRAINT `agendamento_ibfk_3` FOREIGN KEY (`id_tipo_consulta`) REFERENCES `tipo_consulta` (`id_tipo_consulta`),
  CONSTRAINT `agendamento_ibfk_4` FOREIGN KEY (`id_dentista`) REFERENCES `dentista` (`id_dentista`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Estrutura para tabela `clinica`
CREATE TABLE `clinica` (
  `id_clinica` int(11) NOT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `cidade` varchar(30) DEFAULT NULL,
  `endereco` varchar(50) DEFAULT NULL,
  `telefone` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id_clinica`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Estrutura para tabela `dentista`
CREATE TABLE `dentista` (
  `id_dentista` int(11) NOT NULL AUTO_INCREMENT,
  `id_clinica` int(11) DEFAULT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `sexo` char(1) DEFAULT NULL,
  `telefone` varchar(11) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_dentista`),
  KEY `id_clinica` (`id_clinica`),
  CONSTRAINT `dentista_ibfk_1` FOREIGN KEY (`id_clinica`) REFERENCES `clinica` (`id_clinica`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=4;

-- Estrutura para tabela `paciente`
CREATE TABLE `paciente` (
  `id_paciente` int(11) NOT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `cpf` char(11) DEFAULT NULL,
  `dt_nascimento` date DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `sexo` char(1) DEFAULT NULL,
  PRIMARY KEY (`id_paciente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE paciente MODIFY COLUMN id_paciente INT(11) NOT NULL AUTO_INCREMENT;


-- Estrutura para tabela `secretaria`
CREATE TABLE `secretaria` (
  `id_secretaria` int(11) NOT NULL AUTO_INCREMENT,
  `id_clinica` int(11) DEFAULT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `sexo` char(1) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_secretaria`),
  KEY `id_clinica` (`id_clinica`),
  CONSTRAINT `secretaria_ibfk_1` FOREIGN KEY (`id_clinica`) REFERENCES `clinica` (`id_clinica`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=11;
ALTER TABLE `agendamento` MODIFY `id_agenda` INT(11) NOT NULL AUTO_INCREMENT;

-- Estrutura para tabela `tipo_consulta`
CREATE TABLE `tipo_consulta` (
  `id_tipo_consulta` int(11) NOT NULL,
  `preco` decimal(5,2) DEFAULT NULL,
  `descricao` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_tipo_consulta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE `tipo_consulta` MODIFY `id_tipo_consulta` int(11) NOT NULL AUTO_INCREMENT;


-- Estrutura para tabela `usuarios`
CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `cpf` char(11) NOT NULL,
  `senha` varchar(15) NOT NULL,
  `cargo` varchar(20) NOT NULL,
  `id_dentista` int(11) DEFAULT NULL,
  `id_secretaria` int(11) DEFAULT NULL,
  `admin` char(1) DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `cpf` (`cpf`),
  KEY `id_dentista` (`id_dentista`),
  KEY `id_secretaria` (`id_secretaria`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_dentista`) REFERENCES `dentista` (`id_dentista`),
  CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`id_secretaria`) REFERENCES `secretaria` (`id_secretaria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=10;

-- Modelo ER (Entidade-Relacionamento)
-- agendamento

-- id_agenda
-- id_secretaria
-- id_paciente
-- id_tipo_consulta
-- id_dentista
-- tipo_consulta
-- descricao
-- realizado
-- horario
-- dt_agenda
-- preco_final_agendamento
-- clinica

-- id_clinica
-- nome
-- cidade
-- endereco
-- telefone
-- dentista

-- id_dentista
-- id_clinica
-- nome
-- sexo
-- telefone
-- email
-- paciente

-- id_paciente
-- nome
-- cpf
-- dt_nascimento
-- telefone
-- sexo
-- secretaria

-- id_secretaria
-- id_clinica
-- nome
-- sexo
-- email
-- tipo_consulta

-- id_tipo_consulta
-- preco
-- descricao
-- usuarios

-- id_usuario
-- cpf
-- senha
-- cargo
-- id_dentista
-- id_secretaria
-- admin
-- DER (Diagrama de Entidade-Relacionamento)
-- Para criar o DER, recomendo usar uma ferramenta como o MySQL Workbench ou um software de modelagem de dados, como o Draw.io ou o Lucidchart. Você pode usar as informações acima para construir o diagrama visualmente. Aqui está um exemplo simplificado de como as entidades e os relacionamentos podem ser representados:

-- agendamento está relacionado a secretaria, paciente, tipo_consulta e dentista através de chaves estrangeiras.
-- dentista e secretaria estão relacionados a clinica através de chaves estrangeiras.
-- usuarios está relacionado a dentista e secretaria através de chaves estrangeiras.
-- Se precisar de mais detalhes ou ajuda com a construção do DER, estou à disposição!