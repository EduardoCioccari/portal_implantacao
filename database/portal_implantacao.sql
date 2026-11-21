-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 21/11/2026 às 17:25
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `portal_implantacao`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `implantacao_documentos`
--

CREATE TABLE `implantacao_documentos` (
  `id` int(10) UNSIGNED NOT NULL,
  `cliente_id` int(10) UNSIGNED NOT NULL,
  `pdf_consolidado_path` varchar(255) NOT NULL,
  `pdf_assinado_path` varchar(255) DEFAULT NULL,
  `data_geracao` datetime NOT NULL DEFAULT current_timestamp(),
  `data_upload` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `implantacao_documentos`
--

INSERT INTO `implantacao_documentos` (`id`, `cliente_id`, `pdf_consolidado_path`, `pdf_assinado_path`, `data_geracao`, `data_upload`) VALUES
(2, 5, 'conclusao_cliente_5.pdf', NULL, '2026-11-20 11:48:50', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `item_observacoes`
--

CREATE TABLE `item_observacoes` (
  `id` int(10) UNSIGNED NOT NULL,
  `item_id` int(10) UNSIGNED NOT NULL,
  `usuario_id` int(10) UNSIGNED NOT NULL,
  `tipo` enum('execucao','validacao','devolucao') NOT NULL,
  `texto` text NOT NULL,
  `data_registro` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `item_observacoes`
--

INSERT INTO `item_observacoes` (`id`, `item_id`, `usuario_id`, `tipo`, `texto`, `data_registro`) VALUES
(1, 1, 2, 'execucao', 'Reunião realizada com a diretoria e a equipe administrativa. Todos os responsáveis foram definidos.', '2026-09-09 16:00:00'),
(2, 1, 1, 'validacao', 'Ata recebida e conferida. Escopo aprovado.', '2026-09-10 09:30:00'),
(3, 2, 2, 'execucao', 'Cadastramos 312 doadores. Alguns estavam sem e-mail e ficaram apenas com telefone.', '2026-09-28 11:40:00'),
(4, 2, 1, 'validacao', 'Base conferida por amostragem. Cadastro dentro do esperado.', '2026-09-29 15:10:00'),
(5, 3, 2, 'execucao', 'Importação concluída. Tivemos dificuldade com o formato das datas de nascimento, mas corrigimos na planilha antes de importar.', '2026-10-24 17:05:00'),
(6, 4, 2, 'execucao', 'Treinamento feito com duas pessoas do financeiro.', '2026-10-28 10:00:00'),
(7, 4, 1, 'devolucao', 'A conciliação bancária não foi abordada no treinamento. Favor agendar uma nova sessão cobrindo esse tópico.', '2026-10-29 14:20:00'),
(8, 7, 3, 'execucao', 'Reunião realizada com toda a coordenação.', '2026-08-14 10:00:00'),
(9, 7, 1, 'validacao', 'Ok, escopo validado.', '2026-08-14 16:00:00'),
(10, 8, 3, 'execucao', 'Todos os 48 voluntários foram cadastrados.', '2026-09-03 09:00:00'),
(11, 8, 1, 'validacao', 'Conferido, cadastro completo.', '2026-09-04 11:00:00'),
(12, 9, 3, 'execucao', 'Treinamento realizado em duas turmas.', '2026-09-22 18:00:00'),
(13, 9, 1, 'validacao', 'Lista de presença recebida.', '2026-09-23 10:00:00'),
(14, 10, 3, 'execucao', 'Sistema em uso há duas semanas sem problemas.', '2026-10-08 14:00:00'),
(15, 10, 1, 'validacao', 'Implantação homologada.', '2026-10-09 09:00:00'),
(16, 11, 4, 'execucao', 'Reunião feita on-line.', '2026-10-29 10:00:00'),
(17, 11, 1, 'validacao', 'Validado.', '2026-10-29 15:00:00'),
(18, 12, 4, 'execucao', 'Dados enviados por e-mail ao analista.', '2026-11-03 13:45:00'),
(19, 15, 5, 'execucao', 'Realizarmos a parametrização sem dificuldades.', '2026-11-17 19:18:53'),
(20, 15, 1, 'validacao', 'Parametrizção realizada corretamente', '2026-11-17 20:45:42'),
(21, 16, 5, 'execucao', 'Importado todo plano de contas.', '2026-11-18 21:09:05'),
(22, 16, 1, 'validacao', 'Validado.', '2026-11-18 21:09:36'),
(23, 17, 5, 'execucao', 'Lançamentos financeiros feitos manualmente com sucesso.', '2026-11-20 11:48:30'),
(24, 17, 1, 'validacao', 'Validado.', '2026-11-20 11:48:48');

-- --------------------------------------------------------

--
-- Estrutura para tabela `itens`
--

CREATE TABLE `itens` (
  `id` int(10) UNSIGNED NOT NULL,
  `cliente_id` int(10) UNSIGNED NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `descricao` text NOT NULL,
  `prazo_previsto` date NOT NULL,
  `status` enum('pendente','aguardando_validacao','concluido') NOT NULL DEFAULT 'pendente',
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `itens`
--

INSERT INTO `itens` (`id`, `cliente_id`, `titulo`, `descricao`, `prazo_previsto`, `status`, `data_criacao`) VALUES
(1, 2, 'Reunião de kickoff', 'Reunião inicial para apresentação do cronograma, responsáveis e escopo da implantação.', '2026-09-10', 'concluido', '2026-09-01 10:20:00'),
(2, 2, 'Cadastro de doadores', 'Cadastrar os doadores ativos no sistema, com dados de contato e histórico de doações.', '2026-09-30', 'concluido', '2026-09-01 10:22:00'),
(3, 2, 'Importação da base de beneficiários', 'Importar a planilha de beneficiários atendidos utilizando o modelo fornecido pela consultoria.', '2026-10-25', 'aguardando_validacao', '2026-09-01 10:25:00'),
(4, 2, 'Treinamento da equipe financeira', 'Realizar o treinamento do módulo financeiro (contas a pagar, receber e conciliação bancária).', '2026-10-30', 'pendente', '2026-09-01 10:28:00'),
(5, 2, 'Relatórios de prestação de contas', 'Configurar e emitir os relatórios de prestação de contas exigidos pelos convênios.', '2026-11-20', 'pendente', '2026-09-01 10:30:00'),
(6, 2, 'Homologação final', 'Validar o uso do sistema em produção por uma semana sem pendências críticas.', '2026-12-05', 'pendente', '2026-09-01 10:32:00'),
(7, 3, 'Reunião de kickoff', 'Reunião inicial de alinhamento.', '2026-08-15', 'concluido', '2026-08-10 15:00:00'),
(8, 3, 'Cadastro de voluntários', 'Cadastrar voluntários e suas áreas de atuação.', '2026-09-05', 'concluido', '2026-08-10 15:02:00'),
(9, 3, 'Treinamento dos usuários', 'Treinamento geral da equipe no uso do sistema.', '2026-09-25', 'concluido', '2026-08-10 15:04:00'),
(10, 3, 'Homologação final', 'Uso assistido em produção.', '2026-10-10', 'concluido', '2026-08-10 15:06:00'),
(11, 4, 'Reunião de kickoff', 'Reunião inicial de alinhamento.', '2026-10-30', 'concluido', '2026-10-20 09:30:00'),
(12, 4, 'Envio da logo e dados institucionais', 'Enviar a logo em alta resolução, CNPJ e dados de endereço para configuração.', '2026-11-06', 'aguardando_validacao', '2026-10-20 09:32:00'),
(13, 4, 'Cadastro de projetos sociais', 'Cadastrar os projetos sociais ativos com metas e indicadores.', '2026-11-25', 'pendente', '2026-10-20 09:34:00'),
(14, 3, 'Cadastrar lançamentos Financeiros', 'Fazer o cadastro de lançamentos financeiros retroativos via planilha.', '2026-11-20', 'pendente', '2026-11-17 19:13:32'),
(15, 5, 'Parametrizar usuários', 'Parametrizar permissões de usuários no sistema.', '2026-11-20', 'concluido', '2026-11-17 19:16:17'),
(16, 5, 'Importar plano de contas', 'Importar plano de contas contábil completo no sistema', '2026-12-30', 'concluido', '2026-11-18 21:08:39'),
(17, 5, 'Lançamentos Financeiros', 'Fazer os lançamentos de receitas e despesas.', '2026-12-30', 'concluido', '2026-11-20 11:47:55');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha_hash` varchar(255) NOT NULL,
  `tipo` enum('analista','cliente') NOT NULL,
  `assinatura_imagem` varchar(255) DEFAULT NULL,
  `analista_id` int(10) UNSIGNED DEFAULT NULL,
  `data_cadastro` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha_hash`, `tipo`, `assinatura_imagem`, `analista_id`, `data_cadastro`) VALUES
(1, 'Eduardo Cioccari', 'analista@portal.com', '$2y$12$Byf/edVabFRUT4NlyR7yW.syc7eC5LvPZprE5WMVt6ZFFNyC5cJWi', 'analista', 'analista_1_1795185892.png', NULL, '2026-08-01 08:00:00'),
(2, 'Instituto Esperança', 'cliente@esperanca.org', '$2y$10$NXSncgZgCfp8BNC9nvXhDOZEP4w4P/EHvjnn3XYxOAnCR15NIgi8C', 'cliente', NULL, 1, '2026-09-01 10:00:00'),
(3, 'Associação Mãos Dadas', 'cliente@maosdadas.org', '$2y$10$NXSncgZgCfp8BNC9nvXhDOZEP4w4P/EHvjnn3XYxOAnCR15NIgi8C', 'cliente', NULL, 1, '2026-08-10 14:30:00'),
(4, 'Fundação Semear', 'cliente@semear.org', '$2y$10$NXSncgZgCfp8BNC9nvXhDOZEP4w4P/EHvjnn3XYxOAnCR15NIgi8C', 'cliente', NULL, 1, '2026-10-20 09:15:00'),
(5, 'Mão Amiga', 'cliente@maoamiga.com', '$2y$10$eXWJLSLWlu6SVWJ10.W5y.jVyq4tQubkFzCCBbTa0KyCqgZOlplLO', 'cliente', NULL, 1, '2026-11-13 21:32:19');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `implantacao_documentos`
--
ALTER TABLE `implantacao_documentos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_documentos_cliente` (`cliente_id`);

--
-- Índices de tabela `item_observacoes`
--
ALTER TABLE `item_observacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_obs_item` (`item_id`,`data_registro`),
  ADD KEY `idx_obs_usuario` (`usuario_id`);

--
-- Índices de tabela `itens`
--
ALTER TABLE `itens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_itens_cliente_status` (`cliente_id`,`status`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_usuarios_email` (`email`),
  ADD KEY `idx_usuarios_analista` (`analista_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `implantacao_documentos`
--
ALTER TABLE `implantacao_documentos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `item_observacoes`
--
ALTER TABLE `item_observacoes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de tabela `itens`
--
ALTER TABLE `itens`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `implantacao_documentos`
--
ALTER TABLE `implantacao_documentos`
  ADD CONSTRAINT `fk_documentos_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE;

--
-- Restrições para tabelas `item_observacoes`
--
ALTER TABLE `item_observacoes`
  ADD CONSTRAINT `fk_obs_item` FOREIGN KEY (`item_id`) REFERENCES `itens` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_obs_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE;

--
-- Restrições para tabelas `itens`
--
ALTER TABLE `itens`
  ADD CONSTRAINT `fk_itens_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE;

--
-- Restrições para tabelas `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_analista` FOREIGN KEY (`analista_id`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
