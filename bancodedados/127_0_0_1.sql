-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 01-Ago-2015 às 20:11
-- Versão do servidor: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cyberfestival`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `bandas`
--

CREATE TABLE IF NOT EXISTS `bandas` (
  `id` int(255) NOT NULL AUTO_INCREMENT COMMENT 'Identificação da banda',
  `nome` varchar(300) NOT NULL COMMENT 'Nome da banda',
  `email` varchar(100) NOT NULL COMMENT 'Email de contato para a banda',
  `estilo` varchar(300) NOT NULL COMMENT 'Estilo muscial da banda',
  `cidade` varchar(300) NOT NULL COMMENT 'Cidade de fundação da banda',
  `data` date NOT NULL COMMENT 'Data de formação da banda',
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`,`email`),
  KEY `id_3` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Tabela que contém informações das bandas' AUTO_INCREMENT=9 ;

--
-- Extraindo dados da tabela `bandas`
--

INSERT INTO `bandas` (`id`, `nome`, `email`, `estilo`, `cidade`, `data`) VALUES
(8, 'Esmeralda Villalobos', 'arthur_adolfo@hotmail.com', 'Rock', 'Porto Alegre', '2015-07-28');

-- --------------------------------------------------------

--
-- Estrutura da tabela `estilos_musicais`
--

CREATE TABLE IF NOT EXISTS `estilos_musicais` (
  `id` int(255) NOT NULL AUTO_INCREMENT COMMENT 'Id que aponta para o estilo',
  `nome` varchar(300) NOT NULL COMMENT 'Nome do estilo musical',
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Tabela que contém os estilos musicais que podem ser adicionados aos usuários' AUTO_INCREMENT=6 ;

--
-- Extraindo dados da tabela `estilos_musicais`
--

INSERT INTO `estilos_musicais` (`id`, `nome`) VALUES
(2, 'forró'),
(3, 'grunge'),
(4, 'pop'),
(5, 'rap'),
(1, 'rock');

-- --------------------------------------------------------

--
-- Estrutura da tabela `fans_banda`
--

CREATE TABLE IF NOT EXISTS `fans_banda` (
  `id_banda` int(255) NOT NULL COMMENT 'Identificador que relaciona o fã à banda',
  `id_fan` int(255) NOT NULL COMMENT 'Identificador que relaciona o fã à banda',
  KEY `id_banda` (`id_banda`) COMMENT 'Id que relaciona ao id da banda',
  KEY `id_fan` (`id_fan`) COMMENT 'Id que relaciona o id do usuario'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabela que relaciona usuários fãs às bandas';

-- --------------------------------------------------------

--
-- Estrutura da tabela `fotos`
--

CREATE TABLE IF NOT EXISTS `fotos` (
  `id` int(255) NOT NULL AUTO_INCREMENT COMMENT 'Id da foto',
  `id_usuario` int(255) NOT NULL COMMENT 'Id do usuário da foto',
  `nome` varchar(100) NOT NULL COMMENT 'Nome da foto',
  `caminho` varchar(300) NOT NULL COMMENT 'Caminho url para a foto',
  `descricao` text NOT NULL COMMENT 'Descrição para a foto',
  `tipo` int(10) NOT NULL COMMENT 'Tipo de foto. Ex. 0 - Foto de perfil, foto de evento, foto da banda',
  `data` date NOT NULL COMMENT 'Data em que a foto foi enviada',
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`,`caminho`),
  KEY `id_usuriario` (`id_usuario`),
  KEY `caminho` (`caminho`) COMMENT 'Caminho url da foto'
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `fotos`
--

INSERT INTO `fotos` (`id`, `id_usuario`, `nome`, `caminho`, `descricao`, `tipo`, `data`) VALUES
(3, 13, 'cristian.jpg', 'C:/wamp/www/CyberFestival/uploads/usuarios/fotosperfil/cristian.jpg', 'Foto de perfil', 1, '2015-07-28');

-- --------------------------------------------------------

--
-- Estrutura da tabela `fotos_perfil`
--

CREATE TABLE IF NOT EXISTS `fotos_perfil` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificação da foto de perfil no sistema',
  `id_usuario` int(11) NOT NULL COMMENT 'Identificação do usuário ao qual a foto pertence',
  `nome` varchar(100) NOT NULL COMMENT 'Nome da foto',
  `caminho` varchar(300) NOT NULL COMMENT 'Link para a foto',
  `data` date NOT NULL COMMENT 'Data de envio da foto',
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`,`caminho`),
  KEY `id_usuario` (`id_usuario`) COMMENT 'Id que relaciona a foto de perfil ao usuário',
  KEY `Caminho` (`caminho`) COMMENT 'Caminho url da foto de perfil'
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Tabela que contém as informações das fotos de perfil dos usários' AUTO_INCREMENT=25 ;

--
-- Extraindo dados da tabela `fotos_perfil`
--

INSERT INTO `fotos_perfil` (`id`, `id_usuario`, `nome`, `caminho`, `data`) VALUES
(24, 13, 'cristian.jpg', 'C:/wamp/www/CyberFestival/uploads/usuarios/fotosperfil/cristian.jpg', '2015-07-28');

-- --------------------------------------------------------

--
-- Estrutura da tabela `instrumentos`
--

CREATE TABLE IF NOT EXISTS `instrumentos` (
  `id` int(255) NOT NULL AUTO_INCREMENT COMMENT 'Identificação do instrumento',
  `instrumento` varchar(300) NOT NULL COMMENT 'Nome do instrumento',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabela que contém os instrumentos disponíveis no CyberFestival' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `integrantes_banda`
--

CREATE TABLE IF NOT EXISTS `integrantes_banda` (
  `id_banda` int(255) NOT NULL COMMENT 'Identificação que relaciona o integrante à banda',
  `id_usuario` int(255) NOT NULL COMMENT 'Identificação que relciona o integrante à banda',
  `funcao` varchar(300) NOT NULL COMMENT 'Função que o integrante exere na banda. Ex. Vocalista ou Guitarrista',
  KEY `id_banda` (`id_banda`) COMMENT 'Id que relaciona ao id da banda',
  KEY `id_usuario` (`id_usuario`) COMMENT 'Id que relaciona ao id do usuário'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabela que relaciona integrantes à banda';

--
-- Extraindo dados da tabela `integrantes_banda`
--

INSERT INTO `integrantes_banda` (`id_banda`, `id_usuario`, `funcao`) VALUES
(8, 13, 'baterista');

-- --------------------------------------------------------

--
-- Estrutura da tabela `interesse_musical`
--

CREATE TABLE IF NOT EXISTS `interesse_musical` (
  `id_usuario` int(255) NOT NULL COMMENT 'Identificação que relaciona o usuário ao estilo',
  `id_estilo` int(255) NOT NULL COMMENT 'Identificação que relaciona o estilo ao usuário',
  KEY `id_estilo` (`id_estilo`) COMMENT 'Relaciona com o id da tabela estilosmusicais',
  KEY `id_usuario` (`id_usuario`) COMMENT 'relaciona com o id da tabela usuarios'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabela que contém a relação entre usuário e estilo musical';

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario_instrumentos`
--

CREATE TABLE IF NOT EXISTS `usuario_instrumentos` (
  `id_usuario` int(255) NOT NULL COMMENT 'Identificação que relaciona usuário ao instrumento',
  `id_instrumentos` int(255) NOT NULL COMMENT 'Identificaçõ que relaciona o instrumento ao usuário',
  KEY `id_usuario` (`id_usuario`) COMMENT 'Índice que relaciona id ao usuário',
  KEY `id_instrumentos` (`id_instrumentos`) COMMENT 'Relaciona id ao instrumento'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabela que relaciona instrumentos aos usuários';

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(255) NOT NULL AUTO_INCREMENT COMMENT 'Identificação única do usuário',
  `nome` varchar(100) NOT NULL COMMENT 'Nome do usuário',
  `email` varchar(100) NOT NULL COMMENT 'Email de contato do usuário',
  `nacionalidade` varchar(100) NOT NULL COMMENT 'Nacionalidade do usuário',
  `senha` varchar(300) NOT NULL COMMENT 'Senha do usuário (encriptografada)',
  `tipo` int(4) NOT NULL COMMENT 'tipo de usuario: 1-Artista 2-Fã 3-Produtor',
  `estilo` varchar(100) NOT NULL COMMENT 'Estilo de música principal',
  `status` int(5) NOT NULL COMMENT 'Status: 0-destivado 1-ativado 2-esperando verificacao do email 3-redefinicao de senha 4-troca de perfil',
  `data` date NOT NULL COMMENT 'Data de cadastro do usuário',
  `codigoVerificacao` varchar(300) DEFAULT NULL COMMENT 'Código de verificação mandada por email',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `nome` (`nome`),
  UNIQUE KEY `nome_2` (`nome`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Tabela que contém dados principais de usuários' AUTO_INCREMENT=32 ;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `nacionalidade`, `senha`, `tipo`, `estilo`, `status`, `data`, `codigoVerificacao`) VALUES
(1, 'a', '', '', '', 0, '', 0, '0000-00-00', NULL),
(2, 'b', 'as', '', '', 0, '', 0, '0000-00-00', NULL),
(13, 'cristian', 'arthur_adolfo@hotmail.com', 'Brasileiro', '$2y$14$93495055b3fd23d21ce0.urOkh5CF1OjlIMUfnensoGbTxYA2ymPy', 1, 'rock', 1, '0000-00-00', '$2y$05$191252367655b3fd258b7uEtiZf4BpXTup5wAN1FN5v/nAj2Lk6lS');

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `fans_banda`
--
ALTER TABLE `fans_banda`
  ADD CONSTRAINT `fans_banda_ibfk_1` FOREIGN KEY (`id_fan`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fans_banda_ibfk_2` FOREIGN KEY (`id_banda`) REFERENCES `bandas` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `fotos`
--
ALTER TABLE `fotos`
  ADD CONSTRAINT `fotos_ibfk_2` FOREIGN KEY (`caminho`) REFERENCES `fotos_perfil` (`caminho`) ON DELETE CASCADE,
  ADD CONSTRAINT `fotos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `fotos_perfil`
--
ALTER TABLE `fotos_perfil`
  ADD CONSTRAINT `fotos_perfil_ibfk_2` FOREIGN KEY (`caminho`) REFERENCES `fotos` (`caminho`) ON DELETE CASCADE,
  ADD CONSTRAINT `fotos_perfil_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `integrantes_banda`
--
ALTER TABLE `integrantes_banda`
  ADD CONSTRAINT `integrantes_banda_ibfk_1` FOREIGN KEY (`id_banda`) REFERENCES `bandas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `integrantes_banda_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `interesse_musical`
--
ALTER TABLE `interesse_musical`
  ADD CONSTRAINT `interesse_musical_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `interesse_musical_ibfk_2` FOREIGN KEY (`id_estilo`) REFERENCES `estilos_musicais` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `usuario_instrumentos`
--
ALTER TABLE `usuario_instrumentos`
  ADD CONSTRAINT `usuario_instrumentos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `usuario_instrumentos_ibfk_2` FOREIGN KEY (`id_instrumentos`) REFERENCES `instrumentos` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
